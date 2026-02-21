<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use function PHPUnit\Framework\returnArgument;

class TaskController extends Controller
{
    //
    public function index()
    {
        $tasks = Task::all();
        return view("tasks", compact("tasks"));
    }

   public function store()
{
    $task = Task::create([
        'caption' => 'task 2 - ' . Str::random(12)
    ]);

    // ارسال متغیر task به ویو
    $html = view('part_tasks', compact('task'))->render();

    return response()->json([
        'html' => $html
    ]);
}
 public function destroy(Request $request)
{
    try {
        $task = Task::findOrFail($request->id);
        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'تسک با موفقیت حذف شد'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'خطا در حذف تسک: ' . $e->getMessage()
        ], 500);
    }
}



}
