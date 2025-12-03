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
        return view('admin.admin-maps', compact('maps'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('maps', 'public');
        }

        Map::create($data);

        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $map = Map::findOrFail($id);
        $data = $this->validateData($request);

        if ($request->hasFile('gambar')) {
            if ($map->gambar) Storage::disk('public')->delete($map->gambar);
            $data['gambar'] = $request->file('gambar')->store('maps', 'public');
        }

        $map->update($data);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $map = Map::findOrFail($id);
        if ($map->gambar) Storage::disk('public')->delete($map->gambar);
        $map->delete();

        return response()->json(['success' => true]);
    }

    private function validateData(Request $request)
    {
        return $request->validate([
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
    }
}
