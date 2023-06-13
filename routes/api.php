<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\BlogController;

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

Route::post('/login', [PersonController::class, 'login']);
Route::post('/register', [PersonController::class, 'register']);
Route::post('/create-blog', [BlogController::class, 'create_Blog']);
Route::get('/get-blogs', [BlogController::class, 'get_Persons_Blogs']);
Route::get('/get-all-blogs', [BlogController::class, 'get_All_Blogs']);
