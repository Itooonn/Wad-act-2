<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    public function show()
    {
        $account = auth()->user()->load('customer');

        return view('users.show', compact('account'));
    }

    public function destroy(Request $request)
    {
        $account = $request->user()->load(['customer.orders', 'customer.account']);

        abort_if($account->isAdmin(), 403);

        DB::transaction(function () use ($account) {
            $customer = $account->customer;

            if ($customer) {
                foreach ($customer->orders as $order) {
                    $order->products()->detach();
                    $order->delete();
                }

                $customer->account?->delete();
                $customer->delete();
            } else {
                $account->delete();
            }
        });

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Account deleted successfully.');
    }
}
