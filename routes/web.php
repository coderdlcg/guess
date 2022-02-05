<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\PageController;

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

Route::get('/',            [PageController::class, 'index'])->name('home');

Route::get('/game/{game}', [PageController::class, 'game']);
Route::get('/history',     [PageController::class, 'history'])->name('history');

Route::post('/processing', [GameController::class, 'processing']);
Route::post('/find_game',  [GameController::class, 'find_game'])->name('find_game');
Route::post('/cancel',     [GameController::class, 'cancel'])->name('cancel');

require __DIR__.'/auth.php';
