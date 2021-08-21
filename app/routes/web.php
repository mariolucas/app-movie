<?php

use App\Http\Controllers\MovieController;
use Illuminate\Support\Facades\Route;

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

Route::post('/search',[MovieController::class, 'search'])->name('movies.search');
Route::get('/search/page/{page}',[MovieController::class, 'search'])->name('movies.search.page');
Route::get('/',[MovieController::class, 'trending'])->name('movies.trending');
Route::get('/page/{page}',[MovieController::class, 'trending'])->name('movies.trending.page');
Route::get('/details/{id}',[MovieController::class, 'details'])->name('movies.details');