<?php

use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MainController::class, 'StarGame'])->name('StarGame');
Route::post('/',[MainController::class, 'prepareGame'])->name('prepareGame');

