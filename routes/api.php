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
    // Public route - get approved curhats
    Route::get('/approved', [CurhatAnonController::class, 'getApprovedCurhats']);
    
    // CRUD operations
    Route::get('/', [CurhatAnonController::class, 'index']);
    Route::post('/load-data', [CurhatAnonController::class, 'loadData']);
    Route::post('/', [CurhatAnonController::class, 'store']);
    Route::get('/{id}', [CurhatAnonController::class, 'show']);
    Route::put('/{id}', [CurhatAnonController::class, 'update']);
    Route::patch('/{id}', [CurhatAnonController::class, 'update']);
    Route::delete('/{id}', [CurhatAnonController::class, 'destroy']);
    Route::post('/destroy', [CurhatAnonController::class, 'destroy']);
});

// SpadaQuestion API Routes
Route::prefix('spada-question')->group(function () {
    // CRUD operations
    Route::get('/', [SpadaQuestionController::class, 'index']);
    Route::post('/load-data', [SpadaQuestionController::class, 'loadData']);
    Route::post('/', [SpadaQuestionController::class, 'store']);
    Route::get('/{id}', [SpadaQuestionController::class, 'show']);
    Route::put('/{id}', [SpadaQuestionController::class, 'update']);
    Route::patch('/{id}', [SpadaQuestionController::class, 'update']);
    Route::delete('/{id}', [SpadaQuestionController::class, 'destroy']);
    Route::post('/destroy', [SpadaQuestionController::class, 'destroy']);
});

// SpadaAnswer API Routes
Route::prefix('spada-answer')->group(function () {
    // CRUD operations
    Route::get('/', [SpadaAnswerController::class, 'index']);
    Route::post('/load-data', [SpadaAnswerController::class, 'loadData']);
    Route::post('/', [SpadaAnswerController::class, 'store']);
    Route::get('/{id}', [SpadaAnswerController::class, 'show']);
    Route::put('/{id}', [SpadaAnswerController::class, 'update']);
    Route::patch('/{id}', [SpadaAnswerController::class, 'update']);
    Route::delete('/{id}', [SpadaAnswerController::class, 'destroy']);
    Route::post('/destroy', [SpadaAnswerController::class, 'destroy']);
    // Get answers by question ID
    Route::get('/question/{questionId}', [SpadaAnswerController::class, 'getByQuestion']);
});
