<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\Http\Controllers\Controller; 
use Validator; 
use App\Models\Slide;
use App\Laporan;       

class LaporanController extends Controller
{

    public function create()
    {

        $slides = Slide::all(); 
        
        return view('user.pelaporan', [
            'slides' => $slides
        ]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'nullable|string|max:70|regex:/^[a-zA-Z0-9\s]*$/', 
            'no_telepon' => 'required|string|size:12|regex:/^[0-9]*$/', 
            'kecamatan' => 'required|in:Bacukiki,Bacukiki Barat,Ujung,Soreang', 
            'alamat' => 'required|string|max:100',
            'link_postingan' => 'required|url|max:150', 
            'ketinggian_air' => 'nullable|string|max:30',
        ]);

        if ($validator->fails()) {
            return redirect('/pelaporan')
                        ->withErrors($validator)
                        ->withInput();
        }

        $data = $request->all();
        
        if (empty($data['nama'])) {
            $data['nama'] = 'Anonymouse';
        }


        Laporan::create($data);

        return redirect('/pelaporan')->with('success', 'Laporan Anda berhasil terkirim. Terima kasih atas partisipasi Anda.');
    }


    public function index()
    {
        $semua_laporan = Laporan::orderBy('created_at', 'desc')->get();
        return view('admin.kelola_laporan', compact('semua_laporan'));
    }

    public function destroy($id)
    {
        try {
            $laporan = Laporan::findOrFail($id);
            $laporan->delete();
            return redirect('/admin/laporan')->with('success', 'Laporan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect('/admin/laporan')->with('error', 'Gagal menghapus laporan.');
        }
    }
}