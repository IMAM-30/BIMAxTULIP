<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slide;

class HomeController extends Controller
{
    public function index()
    {
        // ambil semua slide yang ada
        $slides = Slide::orderBy('date', 'desc')->get();
        return view('User.home', compact('slides'));
    }
}
