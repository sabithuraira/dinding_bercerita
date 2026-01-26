<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CurhatAnonController;

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
