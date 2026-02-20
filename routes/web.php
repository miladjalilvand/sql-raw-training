<?php

use App\Http\Controllers\SqlTrainingControlller;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



Route::get('/sql-result', [SqlTrainingControlller::class,'train_1'])->name('train_1');
