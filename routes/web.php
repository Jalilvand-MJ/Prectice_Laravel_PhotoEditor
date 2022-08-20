<?php

use App\Http\Controllers\PhotoController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
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

Route::redirect('/', '/photos');

Route::get('/login', [UserController::class, 'phoneEnter'])
    ->name('login');
Route::post('/login', [UserController::class, 'codeSend']);
Route::get('/verify', [UserController::class, 'codeEnter']);
Route::post('/verify', [UserController::class, 'verify']);
Route::get('/logout', [UserController::class, 'logout']);


Route::resource('/photos', PhotoController::class)
    ->except(['update'])
    ->middleware('auth');

