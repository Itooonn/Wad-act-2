<?php

use App\Models\Account;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('regular account can view only the account page', function () {
    $customer = Customer::factory()->create();
    $account = Account::factory()->create([
        'customer_id' => $customer->id,
        'is_admin' => false,
    ]);

    $this->actingAs($account)
        ->get(route('account.show'))
        ->assertOk();
});

test('admin can view an account page', function () {
    $customer = Customer::factory()->create();
    $admin = Account::factory()->create([
        'customer_id' => $customer->id,
        'is_admin' => true,
    ]);

    $this->actingAs($admin)
        ->get(route('account.show'))
        ->assertOk();
});

test('regular account cannot access customer pages', function () {
    $customer = Customer::factory()->create();
    $account = Account::factory()->create([
        'customer_id' => $customer->id,
        'is_admin' => false,
    ]);

    $this->actingAs($account)
        ->get(route('customers.index'))
        ->assertForbidden();

    $this->actingAs($account)
        ->get(route('customers.show', $customer))
        ->assertForbidden();
});

test('regular account cannot manage customers or products', function () {
    $customer = Customer::factory()->create();
    $account = Account::factory()->create([
        'customer_id' => $customer->id,
        'is_admin' => false,
    ]);
    $product = Product::factory()->create();

    $this->actingAs($account)
        ->get(route('customers.edit', $customer))
        ->assertForbidden();

    $this->actingAs($account)
        ->get(route('products.create'))
        ->assertForbidden();

    $this->actingAs($account)
        ->delete(route('products.destroy', $product))
        ->assertForbidden();
});

test('regular account can delete its own account', function () {
    $customer = Customer::factory()->create();
    $account = Account::factory()->create([
        'customer_id' => $customer->id,
        'is_admin' => false,
    ]);

    $this->actingAs($account)
        ->delete(route('account.destroy'))
        ->assertRedirect(route('login'));

    $this->assertDatabaseMissing('accounts', ['id' => $account->id]);
    $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
});

test('admin cannot delete account from account page', function () {
    $customer = Customer::factory()->create();
    $admin = Account::factory()->create([
        'customer_id' => $customer->id,
        'is_admin' => true,
    ]);

    $this->actingAs($admin)
        ->delete(route('account.destroy'))
        ->assertForbidden();
});

test('admin can delete customer with linked account and orders', function () {
    $adminCustomer = Customer::factory()->create();
    $admin = Account::factory()->create([
        'customer_id' => $adminCustomer->id,
        'is_admin' => true,
    ]);

    $customer = Customer::factory()->create();
    $account = Account::factory()->create([
        'customer_id' => $customer->id,
        'is_admin' => false,
    ]);
    $order = Order::factory()->create([
        'customer_id' => $customer->id,
    ]);

    $this->actingAs($admin)
        ->delete(route('customers.destroy', $customer))
        ->assertRedirect(route('customers.index'));

    $this->assertDatabaseMissing('accounts', ['id' => $account->id]);
    $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
});

test('admin cannot add, edit, or delete customers and orders', function () {
    $adminCustomer = Customer::factory()->create();
    $admin = Account::factory()->create([
        'customer_id' => $adminCustomer->id,
        'is_admin' => true,
    ]);
    $customer = Customer::factory()->create();
    $order = Order::factory()->create([
        'customer_id' => $customer->id,
    ]);

    $this->actingAs($admin)
        ->get(route('customers.create'))
        ->assertForbidden();

    $this->actingAs($admin)
        ->get(route('customers.edit', $customer))
        ->assertForbidden();

    $this->actingAs($admin)
        ->get(route('orders.create'))
        ->assertForbidden();

    $this->actingAs($admin)
        ->get(route('orders.edit', $order))
        ->assertForbidden();

    $this->actingAs($admin)
        ->delete(route('orders.destroy', $order))
        ->assertForbidden();
});

test('regular account sees only its own orders', function () {
    $ownedCustomer = Customer::factory()->create();
    $otherCustomer = Customer::factory()->create();
    $account = Account::factory()->create([
        'customer_id' => $ownedCustomer->id,
        'is_admin' => false,
    ]);
    $ownedOrder = Order::factory()->create([
        'customer_id' => $ownedCustomer->id,
    ]);
    $otherOrder = Order::factory()->create([
        'customer_id' => $otherCustomer->id,
    ]);

    $this->actingAs($account)
        ->get(route('orders.index'))
        ->assertOk()
        ->assertSeeText((string) $ownedOrder->id)
        ->assertSeeText($ownedCustomer->name)
        ->assertDontSeeText($otherCustomer->name);
});

test('regular account can only create orders for its own customer', function () {
    $ownedCustomer = Customer::factory()->create();
    $otherCustomer = Customer::factory()->create();
    $account = Account::factory()->create([
        'customer_id' => $ownedCustomer->id,
        'is_admin' => false,
    ]);

    $this->actingAs($account)
        ->post(route('orders.store'), [
            'customer_id' => $otherCustomer->id,
            'order_date' => '2026-04-15',
            'total_amount' => 100,
        ])
        ->assertRedirect(route('orders.index'));

    $this->assertDatabaseHas('orders', [
        'customer_id' => $ownedCustomer->id,
        'order_date' => '2026-04-15',
        'total_amount' => 100,
    ]);
});
