<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use function Symfony\Component\Clock\now;

class SqlTrainingControlller extends Controller
{
    //training one
    public function train_1()
    {
        // $users = User::with(['orders'])->get();
        
        // dd("train 1");
        // dd($users);

        //orders pending 

        $trainig2 = DB::table('orders')->
        select( DB::raw('COUNT(created_at) as count_order') 
        , DB::raw('DATE(created_at) as order_date')
        ,DB::raw('SUM(total_amount) as total_sales') , )->
        groupBy(DB::raw('order_date'))->
        get();



        $training1 = DB::table('orders')->
        where('status' , 'pending')->
        where('created_at', '>', Carbon::now()->subWeek(12))->
        select('user_id' , DB::raw('SUM(total_amount) as total_sales'),
        DB::raw('AVG(total_amount) as avg_sales'),
        DB::raw('COUNT(*) as orders_count'),
        )->
        groupBy('user_id')->
        havingRaw('orders_count >= 2')->
        havingRaw('total_sales > 6683860')->
        get();


       $training3 = DB::table('orders')->
       select( DB::raw('user_id , COUNT(*) as user_count_orders'),
       DB::raw('SUM(total_amount) as user_total_price'),
       )->groupBy('user_id')->
       orderBy('user_total_price' , 'asc')->
       get();

    $training4 = DB::table('orders')
    ->select('user_id', DB::raw("COUNT(CASE WHEN status = 'completed' THEN 1 END) as complated_orders"),
    DB::raw("COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_orders" ))
    ->groupBy('user_id')
    ->get();

     $training5 = DB::table('orders')
    ->select('user_id', DB::raw('MAX(created_at) as last_order'))
    ->groupBy('user_id')
    ->orderBy('last_order' , 'desc')
    ->first();

        dd($training5);

    }
}
