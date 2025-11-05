<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\Slide;
use App\Models\FaqCategory;


class HomeController extends Controller
{
    public function index()
    {
        // Ambil data dari database
        $sections = Section::orderBy('id', 'asc')->get();
        $slides = Slide::orderBy('date', 'desc')->get();

        // Kirim ke view
        return view('User.home', compact('sections', 'slides'));
    }

    public function data()
    {
        $slides = Slide::all();
        return view('User.data', compact('slides'));
    }

    public function maps()
    {
        $slides = Slide::all();
        return view('User.maps', compact('slides'));
    }

    public function pelaporan()
    {
        $slides = Slide::all();
        return view('User.pelaporan', compact('slides'));
    }

    public function faq()
    {
        $slides = \App\Models\Slide::all();
        $categories = \App\Models\FaqCategory::with('faqs')->get();

        return view('User.faq', compact('slides', 'categories'));
    }


    public function kontak()
    {
        $slides = Slide::all();
        return view('User.kontak', compact('slides'));
    }

    
}
