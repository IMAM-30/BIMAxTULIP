<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kontak;
use Illuminate\Http\Request;

class KontakController extends Controller
{

    public function index()
    {
        $items = Kontak::orderBy('order')->paginate(20);
        return view('admin.kontak.index', compact('items'));
    }

    public function create()
    {
        return view('admin.kontak.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'url' => 'nullable|url|max:255',
            'order' => 'nullable|integer',
        ]);

        Kontak::create($data);
        return redirect()->route('admin.kontak.index')->with('success', 'Kontak berhasil ditambahkan.');
    }

    public function edit(Kontak $kontak)
    {
        return view('admin.kontak.edit', compact('kontak'));
    }

    public function update(Request $request, Kontak $kontak)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'url' => 'nullable|url|max:255',
            'order' => 'nullable|integer',
        ]);

        $kontak->update($data);
        return redirect()->route('admin.kontak.index')->with('success', 'Kontak berhasil diperbarui.');
    }

    public function destroy(Kontak $kontak)
    {
        $kontak->delete();
        return redirect()->route('admin.kontak.index')->with('success', 'Kontak berhasil dihapus.');
    }
}
