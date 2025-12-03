<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FaqCategory;
use Illuminate\Http\Request;

class FaqCategoryController extends Controller
{
    public function index()
    {
        $categories = FaqCategory::all();
        return view('admin.FaqCategory.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);
        FaqCategory::create($request->only('name'));
        return back()->with('success', 'Kategori berhasil ditambahkan');
    }

    public function destroy(FaqCategory $faqCategory)
    {
        $faqCategory->delete();
        return back()->with('success', 'Kategori dihapus');
    }

    public function updateCategory(Request $request, $id)
    {
        $request->validate(['name' => 'required']);
        $category = FaqCategory::findOrFail($id);
        $category->update(['name' => $request->name]);

        return response()->json([
            'success' => true,
            'category' => $category
        ], 200);
    }
}
