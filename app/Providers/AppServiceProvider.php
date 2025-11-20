<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Section;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
    }


        public function boot()
    {
        View::composer('Components.navbar', function ($view) {
            View::share('sections', Section::orderBy('id')->get());
        });
    }

    
}
