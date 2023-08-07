<?php

use App\Http\Controllers\Tag\CheckTagController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tag Routes
|--------------------------------------------------------------------------
| タグ用のルート
|
*/

Route::post('/checkTag', CheckTagController::class)->name('checkTag');
