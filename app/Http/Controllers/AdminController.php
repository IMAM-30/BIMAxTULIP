<?php

namespace App\Http\Controllers;

use App\Models\Slide;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $slides = Slide::orderBy('date','desc')->get();
        return view('Admin.admin-home', compact('slides'));
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
        $validated['image'] = $path;

        Slide::create($validated);

        return redirect()->route('admin-home')->with('success', 'Slide berhasil ditambahkan!');
    }

    public function updateSlide(Request $request, $id)
    {
        $slide = Slide::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if($request->hasFile('image')){
            $path = $request->file('image')->store('slides', 'public');
            $validated['image'] = $path;
        }

        $slide->update($validated);

        return redirect()->route('admin-home')->with('success', 'Slide berhasil diperbarui!');
    }

    public function destroySlide($id)
    {
        $slide = Slide::findOrFail($id);
        $slide->delete();
        return redirect()->route('admin-home')->with('success', 'Slide berhasil dihapus!');
    }
}
