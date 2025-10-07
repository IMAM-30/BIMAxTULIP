<?php

use App\Http\Controllers\MapController;

Route::get('/maps', [MapController::class, 'apiIndex']);
