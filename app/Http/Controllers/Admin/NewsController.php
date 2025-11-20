<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsRequest;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    public function index()
    {
        $items = News::orderBy('order')->paginate(20);
        return view('Admin.news.index', compact('items'));
    }

    public function create()
    {
        return view('Admin.news.create');
    }

    public function store(NewsRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('news', 'public');
        }

        News::create($data);
        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil dibuat.');
    }

    public function edit(News $news)
    {
        return view('Admin.news.edit', compact('news'));
    }

    public function update(NewsRequest $request, News $news)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($news->image && Storage::disk('public')->exists($news->image)) {
                Storage::disk('public')->delete($news->image);
            }
            $data['image'] = $request->file('image')->store('news', 'public');
        }

        $news->update($data);
        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(News $news)
    {
        if ($news->image && Storage::disk('public')->exists($news->image)) {
            Storage::disk('public')->delete($news->image);
        }
        $news->delete();
        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil dihapus.');
    }

    public function show(News $news)
    {
        return redirect()->route('admin.news.edit', $news);
    }
}
