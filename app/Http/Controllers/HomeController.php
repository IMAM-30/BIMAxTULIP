<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\Section;
use App\Models\Slide;
use App\Models\FaqCategory;
use App\Models\MonthlyStat;

class HomeController extends Controller
{
    public function index()
    {
        $sections = Section::orderBy('id', 'asc')->get();
        $slides = Slide::orderBy('date', 'desc')->get();
        $kecamatans = \App\Models\Kecamatan::orderBy('order')->get();
        return view('User.home', compact('sections', 'slides', 'kecamatans'));
    }

    public function data()
    {
        $slides = Slide::orderByDesc('created_at')->get();

        $news = News::orderBy('order', 'asc')
                    ->orderByDesc('published_at')
                    ->paginate(9);

        $availableYears = \App\Models\MonthlyStat::select('year')
                            ->distinct()
                            ->orderByDesc('year')
                            ->pluck('year');

        $year = (int) request('year', ($availableYears->first() ?? date('Y')));

        $monthlyStats = \App\Models\MonthlyStat::where('year', $year)->orderBy('month')->get();

        return view('User.data', compact('slides', 'news', 'monthlyStats', 'availableYears', 'year'));
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
        
        $kontaks = Kontak::orderBy('order')->get();

        $slides = \App\Models\Slide::all(); 
        return view('User.kontak', compact('kontaks', 'slides'));
    }
    
    public function sistemcerdas()
    {
        $slides = Slide::all();
        return view('User.sistemcerdas', compact('slides'));
    }

    
}
