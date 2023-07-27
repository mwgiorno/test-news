<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\SectionController;
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

Route::get('/articles', [ArticleController::class, 'index']);

Route::middleware(['auth:sanctum'])->group(function() {
    Route::get('/articles/{article}', [ArticleController::class, 'get']);
    Route::patch('/articles/{article}', [ArticleController::class, 'update']);
    Route::post('/articles', [ArticleController::class, 'create']);
});

Route::get('/sections', [SectionController::class, 'index']);
Route::get('/sections/{section:slug}', [SectionController::class, 'get']);
Route::patch('/sections/{section:slug}', [SectionController::class, 'update']);
Route::post('/sections', [SectionController::class, 'create']);


require __DIR__.'/auth.php';