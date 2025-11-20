<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OtpRequest;
use App\Laporan;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OtpController extends Controller
{
    protected $otpTtlMinutes = 5; // OTP berlaku 5 menit

    public function requestOtp(Request $request)
    {
        $v = Validator::make($request->all(), [
            'no_telepon' => 'required|regex:/^[0-9]+$/|min:9|max:15',
            'nama' => 'nullable|string|max:70',
        ]);

        if ($v->fails()) {
            Log::warning('OTP validation failed: '.json_encode($v->errors()->all()));
            return response()->json(['status'=>'error','errors'=>$v->errors()], 422);
        }

        $phone = $request->input('no_telepon');
        $name = $request->input('nama');
        Log::info("OTP request received for phone: {$phone} name: ".($name ?: 'N/A'));

        // rate limit
        $recentCount = OtpRequest::where('phone', $phone)
                        ->where('created_at', '>=', Carbon::now()->subMinutes(15))
                        ->count();
        if ($recentCount >= 5) {
            Log::warning("OTP rate limit exceeded for {$phone}");
            return response()->json(['status'=>'error','message'=>'Terlalu banyak permintaan OTP, coba nanti.'], 429);
        }

        $otp = random_int(100000, 999999);
        $hash = Hash::make((string)$otp);

        $otpRequest = OtpRequest::create([
            'phone' => $phone,
            'otp_hash' => $hash,
            'session_id' => (string) Str::uuid(),
            'expires_at' => Carbon::now()->addMinutes($this->otpTtlMinutes),
        ]);

        // Kirim via Fonnte (WhatsApp)
        $result = $this->sendViaFonnte($phone, $otp, $name);

        // Logging detil
        Log::info('OTP send result: '.json_encode($result));

        if (!($result['ok'] ?? false)) {
            // hapus record otp jika gagal kirim
            try { $otpRequest->delete(); } catch (\Throwable $e) { Log::error('Failed delete otpRequest after send fail: '.$e->getMessage()); }
            $msg = $result['msg'] ?? 'Gagal mengirim OTP.';
            return response()->json(['status'=>'error','message'=>'Gagal mengirim OTP. '.$msg], 500);
        }

        // Untuk pengujian lokal kita log kode OTP (hapus/disable di production)
        Log::info("DEBUG OTP for {$phone}: {$otp} (session: {$otpRequest->session_id})");

        $debugOtp = (env('SHOW_DEBUG_OTP') === 'true' || env('APP_ENV') !== 'production') ? $otp : null;

        return response()->json([
            'status'=>'ok',
            'message'=>'OTP terkirim. Periksa WhatsApp Anda.',
            'session_id' => $otpRequest->session_id,
            'expires_in' => $this->otpTtlMinutes * 60,
            'provider' => $result['provider'] ?? 'fonnte',
            'provider_raw' => $result['raw'] ?? null,
            'debug_otp' => $debugOtp,
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $v = Validator::make($request->all(), [
            'session_id' => 'required|string',
            'otp' => 'required|digits:6',
            'nama'=>'nullable|string|max:70',
            'no_telepon'=>'required|regex:/^[0-9]+$/|min:9|max:25',
            'kecamatan'=>'required|in:Bacukiki,Bacukiki Barat,Ujung,Soreang',
            'alamat'=>'required|string|max:100',
            'link_postingan'=>'required|url|max:150',
            'ketinggian_air'=>'nullable|string|max:30',
        ]);

        if ($v->fails()) {
            Log::warning('Verify OTP validation failed: '.json_encode($v->errors()->all()));
            return response()->json(['status'=>'error','errors'=>$v->errors()], 422);
        }

        $sessionId = $request->input('session_id');
        $otpInput = trim($request->input('otp'));
        $phoneInput = $request->input('no_telepon');

        Log::info("Verify OTP attempt: session={$sessionId} phone_input={$phoneInput} otp_input={$otpInput}");

        // Cari berdasarkan session_id saja (session_id seharusnya unik)
        $otpRequest = OtpRequest::where('session_id', $sessionId)->latest()->first();

        if (! $otpRequest) {
            Log::warning("Verify OTP: session not found {$sessionId}");
            return response()->json(['status'=>'error','message'=>'Session OTP tidak ditemukan.'], 404);
        }

        // bandingkan nomor setelah dinormalisasi untuk menghindari mismatch format (0812 vs 62812)
        $storedPhoneNorm = $this->normalizePhone($otpRequest->phone);
        $inputPhoneNorm = $this->normalizePhone($phoneInput);

        Log::info("Verify OTP: stored_phone_norm={$storedPhoneNorm} input_phone_norm={$inputPhoneNorm}");

        if ($storedPhoneNorm !== $inputPhoneNorm) {
            Log::warning("Verify OTP: phone mismatch for session {$sessionId} (stored: {$storedPhoneNorm} vs input: {$inputPhoneNorm})");
            return response()->json(['status'=>'error','message'=>'Nomor telepon tidak cocok dengan session OTP.'], 400);
        }

        // safety: handle expires_at as Carbon or parseable string
        try {
            $expired = $otpRequest->isExpired();
        } catch (\Throwable $e) {
            try {
                $exp = Carbon::parse($otpRequest->expires_at);
                $expired = Carbon::now()->gt($exp);
            } catch (\Throwable $e2) {
                $expired = true;
            }
        }

        if ($expired) {
            try { $otpRequest->delete(); } catch (\Throwable $e) { /* ignore */ }
            Log::info("Verify OTP: expired session {$sessionId}");
            return response()->json(['status'=>'error','message'=>'OTP telah kadaluarsa. Silakan minta ulang.'], 410);
        }

        if ($otpRequest->attempts >= 5) {
            try { $otpRequest->delete(); } catch (\Throwable $e) { /* ignore */ }
            Log::warning("Verify OTP: attempts exceeded {$sessionId}");
            return response()->json(['status'=>'error','message'=>'Batas percobaan OTP terlampaui.'], 429);
        }

        if (! Hash::check($otpInput, $otpRequest->otp_hash)) {
            $otpRequest->increment('attempts');
            Log::info("Verify OTP: incorrect code for session {$sessionId}");
            return response()->json(['status'=>'error','message'=>'Kode OTP salah.'], 401);
        }

        // OTP benar -> simpan laporan
        $data = $request->only(['nama','no_telepon','kecamatan','alamat','link_postingan','ketinggian_air']);
        if (empty($data['nama'])) $data['nama'] = 'Anonymouse';

        // normalisasi nomor saat menyimpan
        $data['no_telepon'] = $this->normalizePhone($data['no_telepon']);

        try {
            Laporan::create($data);
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan Laporan setelah OTP verify: '.$e->getMessage());
            return response()->json(['status'=>'error','message'=>'Gagal menyimpan laporan.'], 500);
        }

        try { $otpRequest->delete(); } catch (\Throwable $e) { /* ignore */ }
        Log::info("OTP verified and laporan saved for phone {$storedPhoneNorm} (session {$sessionId})");

        return response()->json(['status'=>'ok','message'=>'Laporan berhasil terkirim. Terima kasih.']);
    }


 
    protected function sendViaFonnte($phone, $otp, $name = null)
    {
        $token = config('services.sms.fonnte_token');
        $endpoint = config('services.sms.fonnte_endpoint');

        if (empty($token) || empty($endpoint)) {
            Log::error('Fonnte token/endpoint missing in config/services.php or .env');
            return ['ok' => false, 'msg' => 'Fonnte credentials tidak ditemukan', 'raw' => null, 'provider' => 'fonnte'];
        }

        $phoneNorm = $this->normalizePhone($phone);

        // friendly message: gunakan nama jika tersedia
        if (!empty($name)) {
            $safeName = preg_replace('/[^A-Za-z0-9 \-\_\.]/', '', $name);
            $greeting = "Halo {$safeName}! 👋\n";
        } else {
            $greeting = "Halo! 👋\n";
        }

        $message = $greeting
                 . "Kode verifikasi laporan kamu adalah *{$otp}*.\n"
                 . "Kode ini berlaku selama {$this->otpTtlMinutes} menit.\n\n"
                 . "Jangan berikan kode ini kepada siapapun.";

        try {
            $response = Http::withHeaders([
                    'Authorization' => $token,
                    'Accept' => 'application/json',
                ])
                ->timeout(10)
                ->post($endpoint, [
                    'target' => $phoneNorm,
                    'message' => $message,
                ]);

            $body = $response->body();
            Log::info('Fonnte response status='.$response->status().' body='.$body);

            $json = null;
            try { $json = $response->json(); } catch (\Throwable $e) { /* ignore */ }

            if ($response->successful()) {
                if ((is_array($json) && (isset($json['message_id']) || isset($json['status']))) || stripos($body, 'ok') !== false) {
                    return ['ok' => true, 'msg' => 'sent', 'raw' => $json ?? $body, 'provider' => 'fonnte'];
                }
                return ['ok' => true, 'msg' => 'sent (200)', 'raw' => $json ?? $body, 'provider' => 'fonnte'];
            }

            return ['ok' => false, 'msg' => 'HTTP '.$response->status(), 'raw' => $json ?? $body, 'provider' => 'fonnte'];
        } catch (\Exception $e) {
            Log::error('Fonnte exception: '.$e->getMessage());
            return ['ok' => false, 'msg' => 'Exception: '.$e->getMessage(), 'raw' => null, 'provider' => 'fonnte'];
        }
    }

    protected function normalizePhone($phone)
    {
        $p = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($p) >= 9 && substr($p,0,1) === '0') {
            return '62'.substr($p,1);
        }
        return $p;
    }
}
