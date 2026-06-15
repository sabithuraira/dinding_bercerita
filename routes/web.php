<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CurhatAnonCommentController;

Route::get('/', [HomeController::class, 'index']);

Route::get('/spada-form', [HomeController::class, 'spadaForm'])->name('spada.form');
Route::post('/spada-form', [HomeController::class, 'storeSpadaAnswer'])->name('spada.store');

Route::get('/curhat-anon/{curhatAnon}/comments', [CurhatAnonCommentController::class, 'index'])
    ->name('curhat-anon.comments.index');
Route::post('/curhat-anon/{curhatAnon}/comments', [CurhatAnonCommentController::class, 'store'])
    ->name('curhat-anon.comments.store');
