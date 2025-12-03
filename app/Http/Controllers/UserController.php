<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slide;
use App\Models\FaqCategory;

class UserController extends Controller
{

    public function faq()
    {
        $slides = class_exists(Slide::class) ? Slide::all() : collect();

        $categories = class_exists(FaqCategory::class)
            ? FaqCategory::with('faqs')->get()
            : collect();

        return view('user.faq', compact('slides', 'categories'));
    }
}
