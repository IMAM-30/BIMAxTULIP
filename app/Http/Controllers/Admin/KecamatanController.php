<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kecamatan;

class KecamatanController extends Controller
{

    public function index()
    {
        $items = Kecamatan::orderBy('order')->paginate(20);
        return view('Admin.kecamatans.index', compact('items'));
    }

    public function create()
    {
        return view('Admin.kecamatans.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'count' => 'nullable|integer|min:0',
            'order' => 'nullable|integer|min:0',
        ]);

        Kecamatan::create($data);

        return redirect()->route('admin.kecamatans.index')->with('success', 'Kecamatan ditambahkan.');
    }

    public function show(Kecamatan $kecamatan)
    {

        return redirect()->route('admin.kecamatans.edit', $kecamatan);
    }

    public function edit(Kecamatan $kecamatan)
    {
        return view('Admin.kecamatans.edit', compact('kecamatan'));
    }

    public function update(Request $request, Kecamatan $kecamatan)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'count' => 'nullable|integer|min:0',
            'order' => 'nullable|integer|min:0',
        ]);

        $kecamatan->update($data);

        return redirect()->route('admin.kecamatans.index')->with('success', 'Kecamatan diperbarui.');
    }

    public function destroy(Kecamatan $kecamatan)
    {
        $kecamatan->delete();
        return redirect()->route('admin.kecamatans.index')->with('success', 'Kecamatan dihapus.');
    }
}
