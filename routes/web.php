<?php

use App\Http\Controllers\SqlTrainingControlller;
use App\Http\Controllers\TaskController;
use App\Models\Task;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    Task::create(['caption' => 'task']);
    return view('welcome');
});

Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');

Route::post('/new-task', [TaskController::class, 'store'])->name('new-task');

Route::delete('/task-destroy', [TaskController::class, 'destroy'])->name('task-destroy');




Route::get('/sql-result', [SqlTrainingControlller::class,'train_1'])->name('train_1');
