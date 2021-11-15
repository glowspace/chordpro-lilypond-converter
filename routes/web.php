<?php

use App\Http\Controllers\ScoreController;
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

Route::get('/', [ScoreController::class, 'start']);

Route::post('/step/2/chords', [ScoreController::class, 'submitChords']);
Route::post('/step/3/score-mixing', [ScoreController::class, 'submitChordProWithScore']);
