<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
//use Illuminate\Http\Request;
//use App\Models\Blog;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/blogs', [BlogController::class, 'show_Blogs']/*function (Request $request) {
    //$data = $request->all();
    return Blog::create([
        'naslov' => 'test blog', //*$data['title']
        'opis' => 'ovo je test blog', //*$data['body']
        'status' => 'U pripremi',
        'datum_kreiranja' => '2023-07-12',
        'datum_objavljivanja' => '2023-07-16',
        'autor_id' => 1
    ]);
}*/);

Route::post('/create-blog', [BlogController::class, 'create_Blog']);
