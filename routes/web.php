<?php

use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MainController::class, 'StartGame'])->name('StartGame');
Route::post('/',[MainController::class, 'prepareGame'])->name('prepareGame');

//in game
Route::get('/game', [MainController::class, 'game'])->name('game');
Route::get('/answer/{enc_answer}', [MainController::class, 'answer'])->name('answer');
Route::get('/next_question', [MainController::class, 'nextQuestion'])->name('nextQuestion');

//game over
Route::get('/show_result', [MainController::class, 'showResults'])->name('showResults');
