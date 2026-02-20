<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
       public function run()
    {
        // ایجاد 10 کاربر با 3 تا 10 سفارش برای هر کدام
       $users = User::factory(10)->create();
        
        foreach ($users as $user) {
            $orderCount = rand(3, 10);
            Order::factory($orderCount)->forUser($user->id)->create();
        }

        // ایجاد 5 کاربر VIP با ایمیل‌های یکتا
        for ($i = 1; $i <= 5; $i++) {
            $vipUser = User::factory()->create([
                'name' => 'VIP User ' . $i,
                'email' => 'vip' . $i . '@example.com', // ایمیل ثابت و یکتا
            ]);
            
            Order::factory(5)
                ->forUser($vipUser->id)
                ->highValue()
                ->create();
        }

        // ایجاد کاربر ادمین
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'], // شرط جستجو
            [   // داده‌های جدید اگر کاربر وجود نداشت
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        Order::factory(15)
            ->forUser($adminUser->id)
            ->create();

        // ایجاد 20 سفارش تصادفی با کاربران تصادفی
        $randomUsers = User::inRandomOrder()->limit(10)->get();
        foreach ($randomUsers as $user) {
            Order::factory(2)->forUser($user->id)->create();
        }

        $this->command->info('Users and Orders seeded successfully!');
        $this->command->info('Total Users: ' . User::count());
        $this->command->info('Total Orders: ' . Order::count());

        $this->command->info('hello console !!!');
    }
}
