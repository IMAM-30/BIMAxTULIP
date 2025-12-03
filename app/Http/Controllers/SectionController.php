<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Section;
use Illuminate\Support\Facades\Storage;

class SectionController extends Controller
{
    public function index()
    {
        $sections = Section::latest()->get();
        return view('user.home', compact('sections'));
    }

    public function admin()
    {
        $sections = Section::latest()->get();
        $slides = \App\Models\Slide::latest()->get(); 
        return view('admin.admin-home', compact('sections', 'slides'));
    }


    public function store(Request $request)
{
    $request->validate([
        'title'       => 'required|string|max:255',
        'subtitle'    => 'nullable|string|max:255',
        'description' => 'required|string',
        'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $path = $request->file('image') ? $request->file('image')->store('sections', 'public') : null;

    Section::create([
        'title'       => $request->title,
        'subtitle'    => $request->subtitle ?? '',
        'description' => $request->description, 
        'image'       => $path,
    ]);

    return redirect('/admin/sections')->with('success', 'Section berhasil ditambahkan!');
}


    public function update(Request $request, $id)
{
    $section = Section::findOrFail($id);

    $request->validate([
        'title'       => 'required|string|max:255',
        'subtitle'    => 'nullable|string|max:255',
        'description' => 'required|string',
        'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    if ($request->hasFile('image')) {
        if ($section->image && Storage::disk('public')->exists($section->image)) {
            Storage::disk('public')->delete($section->image);
        }
        $section->image = $request->file('image')->store('sections', 'public');
    }

    $section->update([
        'title'       => $request->title,
        'subtitle'    => $request->subtitle ?? '',
        'description' => $request->description,
        'image'       => $section->image,
    ]);

    return redirect('/admin/sections')->with('success', 'Section berhasil diperbarui!');
}


    public function destroy($id)
    {
        $section = Section::findOrFail($id);

        if ($section->image && Storage::disk('public')->exists($section->image)) {
            Storage::disk('public')->delete($section->image);
        }

        $section->delete();

        return redirect('/admin')->with('success', 'Section berhasil dihapus!');
    }
}
