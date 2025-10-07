<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Map;
use Illuminate\Support\Facades\Storage;

class MapController extends Controller
{
    public function index()
    {
        $maps = Map::all();
        return view('Admin.admin-maps', compact('maps'));
    }

    // Menangani tambah & update
    public function store(Request $request, $id = null)
    {
        $data = $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'alamat' => 'required|string',
            'ketinggian_air' => 'required|numeric',
            'rumah_terdampak' => 'required|numeric',
            'jumlah_korban' => 'required|numeric',
            'luas_cakupan' => 'required|numeric',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Upload gambar jika ada
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('maps', 'public');
        }

        if ($id) { // update
            $map = Map::findOrFail($id);
            if ($request->hasFile('gambar') && $map->gambar) {
                Storage::disk('public')->delete($map->gambar);
            }
            $map->update($data);
        } else { // create
            Map::create($data);
        }

        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        \Log::info('Update request masuk', [
            'id' => $id,
            'request' => $request->all()
        ]);

        $data = $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'alamat' => 'required|string',
            'ketinggian_air' => 'required|numeric',
            'rumah_terdampak' => 'required|numeric',
            'jumlah_korban' => 'required|numeric',
            'luas_cakupan' => 'required|numeric',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        \Log::info('Data setelah validasi', $data);

        $map = Map::findOrFail($id);

        // Upload gambar jika ada
        if ($request->hasFile('gambar')) {
            if ($map->gambar) {
                Storage::disk('public')->delete($map->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('maps', 'public');
            \Log::info('Gambar baru diupload', ['gambar' => $data['gambar']]);
        }

        $map->update($data);

        \Log::info('Data setelah update', $map->toArray());

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $map = Map::findOrFail($id);

        if ($map->gambar) {
            Storage::disk('public')->delete($map->gambar);
        }

        $map->delete();

        return response()->json(['success' => true]);
    }
}
