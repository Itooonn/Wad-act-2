<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $account = auth()->user();
        $orders = Order::with('customer')
            ->when(
                ! $account->isAdmin(),
                fn ($query) => $query->where('customer_id', $account->customer_id)
            )
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $account = auth()->user();
        abort_if($account->isAdmin(), 403);

        $customers = Customer::query()->whereKey($account->customer_id)->get();

        return view('orders.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $account = auth()->user();
        abort_if($account->isAdmin(), 403);

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_date' => 'required|date',
            'total_amount' => 'required|numeric|min:0',
        ]);

        $validated['customer_id'] = $account->customer_id;

        Order::create($validated);

        return redirect()->route('orders.index')->with('success', 'Order created successfully.');
    }

    public function show(Order $order)
    {
        $this->authorizeOrderAccess($order);

        $order->load(['customer', 'products']);
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        abort(403);
    }

    public function update(Request $request, Order $order)
    {
        abort(403);
    }

    public function destroy(Order $order)
    {
        $this->authorizeOrderManagement($order);

        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }

    private function authorizeOrderAccess(Order $order): void
    {
        $account = auth()->user();

        abort_unless(
            $account->isAdmin() || $order->customer_id === $account->customer_id,
            403
        );
    }

    private function authorizeOrderManagement(Order $order): void
    {
        abort(403);
    }
}
