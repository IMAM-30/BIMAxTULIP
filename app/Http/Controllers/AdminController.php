<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slide;
use App\Models\Section;

class AdminController extends Controller
{
    public function index()
    {
        // Ambil data slide dan section dari database
        $slides = Slide::orderBy('date', 'desc')->get();
        $sections = Section::orderBy('id', 'asc')->get();

        // Kirim ke view admin
        return view('Admin.admin-home', compact('slides', 'sections'));
    }

    public function storeSlide(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'date' => 'required|date',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Simpan gambar ke folder public/storage/slides
        $path = $request->file('image')->store('slides', 'public');

        // Simpan data ke database
        Slide::create([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'],
            'date' => $validated['date'],
            'image' => $path,
        ]);

        // Reload halaman admin setelah tambah
        return redirect('/admin')->with('success', 'Slide berhasil ditambahkan!');
    }

    public function updateSlide(Request $request, Slide $slide)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Jika ada upload gambar baru
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('slides', 'public');
            $slide->image = $path;
        }

        // Update kolom lainnya
        $slide->title = $validated['title'];
        $slide->subtitle = $validated['subtitle'];
        $slide->date = $validated['date'];
        $slide->save();

        // Reload halaman admin setelah update
        return redirect('/admin')->with('success', 'Slide berhasil diperbarui!');
    }

    public function destroySlide(Slide $slide)
    {
        $slide->delete();
        return redirect('/admin')->with('success', 'Slide berhasil dihapus!');
    }
}
