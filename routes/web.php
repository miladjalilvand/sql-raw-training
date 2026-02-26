<?php

use App\Http\Controllers\SqlTrainingControlller;
use App\Http\Controllers\TaskController;
use App\Models\Machine;
use App\Models\MachineFault;
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



Route::get('msql-result' , function(){
    $machines = Machine::with('machine_type')->
    get();

    $machine_fault = MachineFault::join(
        'machine_fault_types as mft',
        'machine_faults.machine_fault_type_id',
        '=',
        'mft.id'
            )
        ->select(
        'machine_faults.*',
        'mft.caption as fault_type_title'
            )
        ->get();



    dd($machine_fault);
});
