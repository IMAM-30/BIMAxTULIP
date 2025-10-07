<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\Slide;

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
}
