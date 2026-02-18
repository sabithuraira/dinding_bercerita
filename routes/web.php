<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index']);

Route::get('/spada-form', [HomeController::class, 'spadaForm'])->name('spada.form');
Route::post('/spada-form', [HomeController::class, 'storeSpadaAnswer'])->name('spada.store');
