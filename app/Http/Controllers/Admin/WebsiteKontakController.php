<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\WebsiteKontakRequest;
use App\Models\WebsiteKontak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WebsiteKontakController extends Controller
{
    public function index()
    {
        $items = WebsiteKontak::orderBy('order')->paginate(20);
        return view('Admin.websitekontak.index', compact('items'));
    }

    public function create()
    {
        return view('Admin.websitekontak.create');
    }

    public function store(WebsiteKontakRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('websitekontak', 'public');
            $data['image'] = $path;
        }

        WebsiteKontak::create($data);
        return redirect()->route('admin.websitekontak.index')->with('success', 'Item berhasil ditambah.');
    }

    public function show(WebsiteKontak $websitekontak)
    {
        return redirect()->route('admin.websitekontak.edit', $websitekontak);
    }

    public function edit(WebsiteKontak $websitekontak)
    {
        return view('Admin.websitekontak.edit', compact('websitekontak'));
    }

    public function update(WebsiteKontakRequest $request, WebsiteKontak $websitekontak)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {

            if ($websitekontak->image && Storage::disk('public')->exists($websitekontak->image)) {
                Storage::disk('public')->delete($websitekontak->image);
            }
            $data['image'] = $request->file('image')->store('websitekontak', 'public');
        }

        $websitekontak->update($data);
        return redirect()->route('admin.websitekontak.index')->with('success', 'Item berhasil diperbarui.');
    }

    public function destroy(WebsiteKontak $websitekontak)
    {
        if ($websitekontak->image && Storage::disk('public')->exists($websitekontak->image)) {
            Storage::disk('public')->delete($websitekontak->image);
        }
        $websitekontak->delete();
        return redirect()->route('admin.websitekontak.index')->with('success', 'Item berhasil dihapus.');
    }
}
