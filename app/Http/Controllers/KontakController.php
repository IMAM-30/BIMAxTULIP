<?php

namespace App\Http\Controllers;

use App\Models\Kontak;
use App\Models\WebsiteKontak;
use App\Models\Slide;

class KontakController extends Controller
{
    public function index()
    {
        $kontaks = Kontak::orderBy('order')->get();
        $slides = Slide::all(); 

        $websitekontaks = WebsiteKontak::orderBy('order')->get();

        return view('User.kontak', compact('kontaks', 'slides', 'websitekontaks'));
    }
}
