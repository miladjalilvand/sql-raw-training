<?php

use App\Http\Controllers\SqlTrainingControlller;
use App\Http\Controllers\TaskController;
use App\Models\Machine;
use App\Models\MachineFault;
use App\Models\Task;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
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


        $machine_join_machine_types = 
        DB::table('machines')->
        // Machine::
        join('machine_types as mt','machine_type_id' , '=' , 'mt.id')->
        get();
        
        //dd($machine_join_machine_types);

        $count_machine_of_machine_types = 
        DB::table('machines')->
        select('machine_type_id' , DB::raw('COUNT(machine_type_id) as count'))->
        groupBy('machine_type_id')->
        get();
        

$from = '2026-01-01 00:00:00';
$to   = '2026-02-27 23:59:59';

        $result = DB::table('machine_fault_histories as mfh')
    ->join('machines as m', 'm.id', '=', 'mfh.machine_id')
    ->join('machine_logs as start_log', 'start_log.id', '=', 'mfh.start_machine_log_id')
    ->leftJoin('machine_logs as end_log', 'end_log.id', '=', 'mfh.end_machine_log_id')
    ->where('start_log.created_at', '<=', $to)
    ->where(function($q) use ($from) {
        $q->where('end_log.created_at', '>=', $from)
          ->orWhereNull('end_log.created_at');
    })
    ->select(
        'm.id',
        'm.model',
        DB::raw("
            SUM(
                TIMESTAMPDIFF(
                    DAY,
                    GREATEST(start_log.created_at, '$from'),
                    LEAST(
                        COALESCE(end_log.created_at, NOW()),
                        '$to'
                    )
                )
            ) as total_fault_minutes
        ")
    )
    ->groupBy('m.id', 'm.model')
    ->get();


    $machines_randeman = DB::table('machine_fault_histories')->
    get();

$rangeMinutes = (strtotime($to) - strtotime($from)) / 60;


// $from = '2026-01-01 00:00:00';
// $to   = '2026-02-27 23:59:59';
$result2 = DB::table('machine_fault_histories as mfh')
    ->join('machines as m', 'm.id', '=', 'mfh.machine_id')
    ->join('machine_logs as start_log', 'start_log.id', '=', 'mfh.start_machine_log_id')
    ->leftJoin('machine_logs as end_log', 'end_log.id', '=', 'mfh.end_machine_log_id')
    ->join('machine_faults as mf', 'mf.id', '=', 'mfh.machine_fault_id')
    ->join('machine_performance_standards as mps', 'mps.machine_id', '=', 'm.id')

    // شرط همپوشانی بازه
    ->where('start_log.created_at', '<=', $to)
    ->where(function($q) use ($from) {
        $q->where('end_log.created_at', '>=', $from)
          ->orWhereNull('end_log.created_at');
    })

    ->select(
        'm.id',
        'm.model',
        'mps.expected_output_per_hour',

        // مجموع دقایق خرابی
        DB::raw("
            SUM(
                TIMESTAMPDIFF(
                    MINUTE,
                    GREATEST(start_log.created_at, '$from'),
                    LEAST(COALESCE(end_log.created_at, NOW()), '$to')
                )
            ) as total_fault_minutes
        "),

        // مجموع دقایق از دست رفته واقعی (با درصد افت)
        DB::raw("
            SUM(
                TIMESTAMPDIFF(
                    MINUTE,
                    GREATEST(start_log.created_at, '$from'),
                    LEAST(COALESCE(end_log.created_at, NOW()), '$to')
                ) * (mf.drop_performance / 100)
            ) as lost_performance_minutes
        ")
    )
    ->groupBy('m.id', 'm.model', 'mps.expected_output_per_hour')
    ->get()
    ->map(function($row) use ($rangeMinutes) {

        $expectedOutput = ($rangeMinutes / 60) * $row->expected_output_per_hour;

        $lostHours = $row->lost_performance_minutes / 60;

        $lostOutput = $lostHours * $row->expected_output_per_hour;

        $realOutput = $expectedOutput - $lostOutput;

        $row->total_fault_hours = $row->total_fault_minutes / 60;
        $row->lost_output = $lostOutput;
        $row->expected_output = $expectedOutput;
        $row->real_output = $realOutput;

        return $row;
    });

// $machines_off_times = DB::table('machine_fault_histories as mfh')
//     ->join('machines as m', 'm.id', '=', 'mfh.machine_id')
//     ->join('machine_logs as startml', 'startml.id', '=', 'mfh.start_machine_log_id')
//     ->leftJoin('machine_logs as endml', 'endml.id', '=', 'mfh.end_machine_log_id')
//     ->select(
//         'mfh.machine_id',
//         'm.model as machine_model', // اضافه کردن نام دستگاه برای شناسایی بهتر
//         DB::raw('SUM(TIMESTAMPDIFF(MINUTE, 
//             startml.created_at, 
//             COALESCE(endml.created_at, NOW())
//         )) as total_fault_minutes')
//     )
//     ->groupBy('mfh.machine_id', 'm.model') // گروه‌بندی بر اساس machine_id
//     ->get();



$machines_off_times = DB::table('machine_fault_histories as mfh')->
join('machines as m', 'm.id' , '=','mfh.machine_id')->
join('machine_logs as start_log','start_log.id','=','mfh.start_machine_log_id')->
leftJoin('machine_logs as end_log','end_log.id','=','mfh.end_machine_log_id')->
select('mfh.machine_id' , 'm.model as model-machine',
DB::raw('SUM(TIMESTAMPDIFF(DAY,
start_log.created_at , 
COALESCE(end_log.created_at  ,NOW())
)) as total_fault '))->groupBy('m.id','model-machine')->get();

$to_date = Carbon::now()->subMonth(2);
$from_date = Carbon::now()->subDay(4);

$machine_off_times_in_current_days = 
DB::table('machine_fault_histories as mfh')->
join('machines as m','m.id','=','mfh.machine_id')->
join('machine_logs as start_log','start_log.id','=','mfh.start_machine_log_id')->
leftJoin('machine_logs as end_log','end_log.id','=','mfh.end_machine_log_id')->
select('mfh.machine_id as mfhmi','m.model',
DB::raw('SUM(
            TIMESTAMPDIFF(
                HOUR,
                GREATEST(start_log.created_at, "' . $from_date . '"),
                LEAST(COALESCE(end_log.created_at, NOW()), "' . $to_date . '")
            )
        ) as total_fault_hours'),)->
groupBy('mfhmi','m.model')->
get();


dd($machine_off_times_in_current_days);
});
