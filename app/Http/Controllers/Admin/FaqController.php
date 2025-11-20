<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;
use App\Models\FaqCategory;

class FaqController extends Controller
{
    public function index()
    {
        $categories = FaqCategory::with('faqs')->get();
        return view('Admin.admin-faq', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate(['name' => 'required']);
        FaqCategory::create($request->only('name'));
        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function storeFaq(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'question' => 'required',
            'answer' => 'required',
            'image1' => 'image|nullable',
            'image2' => 'image|nullable',
        ]);

        $data = $request->all();

        if ($request->hasFile('image1')) {
            $data['image1'] = $request->file('image1')->store('faq_images', 'public');
        }
        if ($request->hasFile('image2')) {
            $data['image2'] = $request->file('image2')->store('faq_images', 'public');
        }

        Faq::create($data);
        return back()->with('success', 'Pertanyaan berhasil ditambahkan.');
    }

    public function updateFaq(Request $request, $id)
    {
        $faq = Faq::findOrFail($id);

        $request->validate([
            'question' => 'required',
            'answer' => 'required',
            'image1' => 'image|nullable',
            'image2' => 'image|nullable',
        ]);

        $data = $request->only('question', 'answer');

        if ($request->hasFile('image1')) {
            $data['image1'] = $request->file('image1')->store('faq_images', 'public');
        }
        if ($request->hasFile('image2')) {
            $data['image2'] = $request->file('image2')->store('faq_images', 'public');
        }

        $faq->update($data);

        return response()->json([
            'success' => true,
            'faq' => $faq
        ], 200);
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



    public function destroyFaq($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();
        return back()->with('success', 'Pertanyaan dihapus.');
    }

    public function destroyCategory($id)
    {
        $cat = FaqCategory::findOrFail($id);
        $cat->delete();
        return back()->with('success', 'Kategori dihapus.');
    }
}
