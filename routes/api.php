<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\SectionController;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/{article}', [ArticleController::class, 'get']);

Route::middleware(['auth:sanctum'])->group(function() {
    Route::patch('/articles/{article}', [ArticleController::class, 'update']);
    Route::post('/articles', [ArticleController::class, 'create']);
    Route::delete('/articles/{article}', [ArticleController::class, 'destroy']);

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
    
    Route::delete('tokens', function(Request $request) {
        $request->user()->tokens()->delete();
    });
});

Route::get('/sections', [SectionController::class, 'index']);
Route::get('/sections/{section:slug}', [SectionController::class, 'get']);
Route::patch('/sections/{section:slug}', [SectionController::class, 'update']);
Route::post('/sections', [SectionController::class, 'create']);