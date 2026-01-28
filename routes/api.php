<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CurhatAnonController;
use App\Http\Controllers\SpadaQuestionController;
use App\Http\Controllers\SpadaAnswerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// CurhatAnon API Routes
Route::prefix('curhat-anon')->group(function () {
    // Custom routes (can't use resource)
    Route::get('/approved', [CurhatAnonController::class, 'getApprovedCurhats']);
    Route::post('/load-data', [CurhatAnonController::class, 'loadData']);
    Route::post('/destroy', [CurhatAnonController::class, 'destroy']);
});

// Standard CRUD using apiResource (excludes create and edit routes)
Route::apiResource('curhat-anon', CurhatAnonController::class);

// SpadaQuestion API Routes
Route::prefix('spada-question')->group(function () {
    // Custom routes (can't use resource)
    Route::post('/load-data', [SpadaQuestionController::class, 'loadData']);
    Route::post('/destroy', [SpadaQuestionController::class, 'destroy']);
});

// Standard CRUD using apiResource (excludes create and edit routes)
Route::apiResource('spada-question', SpadaQuestionController::class);

// SpadaAnswer API Routes
Route::prefix('spada-answer')->group(function () {
    // Custom routes (can't use resource)
    Route::post('/load-data', [SpadaAnswerController::class, 'loadData']);
    Route::post('/destroy', [SpadaAnswerController::class, 'destroy']);
    Route::get('/question/{questionId}', [SpadaAnswerController::class, 'getByQuestion']);
});

// Standard CRUD using apiResource (excludes create and edit routes)
Route::apiResource('spada-answer', SpadaAnswerController::class);
