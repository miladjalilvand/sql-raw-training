<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        $statuses = ['pending', 'processing', 'completed', 'cancelled'];
        
        return [
            'user_id' => User::factory(), // اینجا به صورت خودکار یک کاربر جدید می‌سازد
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'total_amount' => fake()->randomFloat(2, 10000, 5000000),
            'status' => fake()->randomElement($statuses),
            'shipping_address' => fake()->address(),
            'payment_method' => fake()->randomElement(['online', 'cash', 'card']),
            'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
            'updated_at' => function (array $attributes) {
                return fake()->dateTimeBetween($attributes['created_at'], 'now');
            },
        ];
    }

    // state برای سفارشات تکمیل شده
    public function completed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'completed',
            ];
        });
    }

    // state برای سفارشات لغو شده
    public function cancelled()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'cancelled',
            ];
        });
    }

    // state برای سفارشات با مبلغ بالا
    public function highValue()
    {
        return $this->state(function (array $attributes) {
            return [
                'total_amount' => fake()->randomFloat(2, 1000000, 10000000),
            ];
        });
    }

    // متد برای سفارش مربوط به کاربر خاص
    public function forUser($userId)
    {
        return $this->state(function (array $attributes) use ($userId) {
            return [
                'user_id' => $userId,
            ];
        });
    }
}