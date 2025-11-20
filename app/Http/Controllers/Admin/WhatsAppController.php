<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WhatsApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WhatsAppController extends Controller
{
    public function index()
    {
        // tampilkan nomor aktif lebih dulu
        $whatsapps = WhatsApp::orderByDesc('active')->orderByDesc('id')->get();
        return view('admin.whatsapp.index', compact('whatsapps'));
    }

    public function edit(WhatsApp $whatsapp)
    {
        return view('admin.whatsapp.edit', compact('whatsapp'));
    }

    public function create()
    {
        return view('admin.whatsapp.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'phone'   => ['required','string'],
            'message' => ['nullable','string'],
            'label'   => ['nullable','string'],
            'active'  => ['sometimes','boolean'],
            'image'   => ['nullable','image','mimes:png,jpg,jpeg,svg','max:2048'],
        ]);

        // apakah admin menandai sebagai aktif?
        $isActive = $request->has('active') && (bool) $request->active;

        if ($isActive) {
            // matikan yang lain
            WhatsApp::query()->update(['active' => false]);
        }

        if ($request->hasFile('image')) {
            // simpan ke storage/app/public/whatsapp
            $path = $request->file('image')->store('whatsapp', 'public');
            $data['image'] = $path;
        }

        $data['active'] = $isActive;

        WhatsApp::create($data);

        return redirect()->route('admin.whatsapp.index')->with('success', 'Nomor WA tersimpan');
    }

    public function update(Request $request, WhatsApp $whatsapp)
    {
        $data = $request->validate([
            'phone'   => ['required','string'],
            'message' => ['nullable','string'],
            'label'   => ['nullable','string'],
            'active'  => ['sometimes','boolean'],
            'image'   => ['nullable','image','mimes:png,jpg,jpeg,svg','max:2048'],
        ]);

        // apakah admin menandai sebagai aktif?
        $isActive = $request->has('active') && (bool) $request->active;

        if ($isActive) {
            // matikan yang lain
            WhatsApp::query()->update(['active' => false]);
        }

        if ($request->hasFile('image')) {
            // hapus file lama kalau ada
            if ($whatsapp->image && Storage::disk('public')->exists($whatsapp->image)) {
                Storage::disk('public')->delete($whatsapp->image);
            }
            $path = $request->file('image')->store('whatsapp', 'public');
            $data['image'] = $path;
        }

        $data['active'] = $isActive;

        $whatsapp->update($data);

        return redirect()->route('admin.whatsapp.index')->with('success', 'Nomor WA diperbarui');
    }

    public function destroy(WhatsApp $whatsapp)
    {
        // hapus file image jika ada
        if ($whatsapp->image && Storage::disk('public')->exists($whatsapp->image)) {
            Storage::disk('public')->delete($whatsapp->image);
        }

        $whatsapp->delete();

        return back()->with('success', 'Nomor dihapus');
    }
}
