<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slide;
use App\Models\Section;

class AdminController extends Controller
{
    public function index()
    {
        $slides = Slide::orderBy('date', 'desc')->get();
        $sections = Section::orderBy('id', 'asc')->get();

        return view('admin.admin-home', compact('slides', 'sections'));
    }

    public function storeSlide(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'date' => 'required|date',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $path = $request->file('image')->store('slides', 'public');

        Slide::create([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'],
            'date' => $validated['date'],
            'image' => $path,
        ]);

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

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('slides', 'public');
            $slide->image = $path;
        }

        $slide->title = $validated['title'];
        $slide->subtitle = $validated['subtitle'];
        $slide->date = $validated['date'];
        $slide->save();

        return redirect('/admin')->with('success', 'Slide berhasil diperbarui!');
    }

    public function destroySlide(Slide $slide)
    {
        $slide->delete();
        return redirect('/admin')->with('success', 'Slide berhasil dihapus!');
    }
}
