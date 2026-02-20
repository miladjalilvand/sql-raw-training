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
// =============================================
// تمرین ۲: گزارش فروش روزانه
// =============================================
$trainig2 = DB::table('orders')->
    select(
        DB::raw('COUNT(created_at) as count_order'), // تعداد سفارشات در هر روز
        DB::raw('DATE(created_at) as order_date'),   // تبدیل timestamp به تاریخ (بدون ساعت)
        DB::raw('SUM(total_amount) as total_sales'), // مجموع مبلغ فروش در هر روز
    )->
    groupBy(DB::raw('order_date'))-> // گروه‌بندی بر اساس تاریخ (روز)
    get();


// =============================================
// تمرین ۱: شناسایی کاربران VIP با سفارشات بالا
// =============================================
$training1 = DB::table('orders')->
    where('status', 'pending')-> // فقط سفارشات با وضعیت "در انتظار"
    where('created_at', '>', Carbon::now()->subWeek(12))-> // سفارشات ۳ ماه اخیر
    select(
        'user_id',                                      // شناسه کاربر
        DB::raw('SUM(total_amount) as total_sales'),    // مجموع خرید هر کاربر
        DB::raw('AVG(total_amount) as avg_sales'),      // میانگین مبلغ هر سفارش
        DB::raw('COUNT(*) as orders_count'),            // تعداد کل سفارشات هر کاربر
    )->
    groupBy('user_id')->
    havingRaw('orders_count >= 2')->        // کاربرانی که حداقل ۲ سفارش دارند
    havingRaw('total_sales > 6683860')->    // کاربرانی که مجموع خریدشان از آستانه تعیین شده بیشتر است
    get();


// =============================================
// تمرین ۳: رتبه‌بندی کاربران بر اساس مجموع خرید
// =============================================
$training3 = DB::table('orders')->
    select(
        DB::raw('user_id, COUNT(*) as user_count_orders'), // تعداد سفارشات هر کاربر
        DB::raw('SUM(total_amount) as user_total_price'),  // مجموع مبلغ خرید هر کاربر
    )->
    groupBy('user_id')->
    orderBy('user_total_price', 'asc')-> // مرتب‌سازی صعودی (از کمترین به بیشترین خریدار)
    get();


// =============================================
// تمرین ۴: تفکیک وضعیت سفارشات هر کاربر
// =============================================
$training4 = DB::table('orders')
    ->select(
        'user_id',
        DB::raw("COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed_orders"), // تعداد سفارشات تحویل شده
        DB::raw("COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_orders")     // تعداد سفارشات در انتظار
    )
    ->groupBy('user_id')
    ->get();


// =============================================
// تمرین ۵: آخرین سفارش ثبت شده در سیستم
// =============================================
$training5 = DB::table('orders')
    ->select(
        'user_id',
        DB::raw('MAX(created_at) as last_order') // جدیدترین تاریخ سفارش برای هر کاربر
    )
    ->groupBy('user_id')
    ->orderBy('last_order', 'desc') // مرتب‌سازی نزولی بر اساس آخرین سفارش
    ->first(); // فقط اولین رکورد (آخرین سفارش کل سیستم)
        dd($training5);

    }
}
