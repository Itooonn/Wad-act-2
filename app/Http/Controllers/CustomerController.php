<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index()
    {
        $this->authorizeManagement();

        $customers = Customer::query()->latest()->get();

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        abort(403);
    }

    public function store(Request $request)
    {
        abort(403);
    }

    public function show(Customer $customer)
    {
        $this->authorizeManagement();

        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        abort(403);
    }

    public function update(Request $request, Customer $customer)
    {
        abort(403);
    }

    public function destroy(Customer $customer)
    {
        $this->authorizeManagement();

        DB::transaction(function () use ($customer) {
            $customer->loadMissing(['orders', 'account']);

            foreach ($customer->orders as $order) {
                $order->products()->detach();
                $order->delete();
            }

            $customer->account?->delete();
            $customer->delete();
        });

        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }

    private function authorizeManagement(): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
    }
}
