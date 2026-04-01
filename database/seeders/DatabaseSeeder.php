<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Account;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Users
        User::factory(10)->create();

        // Seed Customers
        $customers = Customer::factory(10)->create();

        // Seed Products
        $products = Product::factory(20)->create();

        // Seed Orders and OrderItems
        $customers->each(function ($customer) use ($products) {
            $orders = Order::factory(rand(1, 3))->create(['customer_id' => $customer->id]);
            $orders->each(function ($order) use ($products) {
                $orderProducts = $products->random(rand(1, 5));
                foreach ($orderProducts as $product) {
                    OrderItem::factory()->create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                    ]);
                }
            });
        });

        // Seed Accounts
        $customers->each(function ($customer) {
            Account::factory()->create([
                'customer_id' => $customer->id,
            ]);
        });
    }
}
