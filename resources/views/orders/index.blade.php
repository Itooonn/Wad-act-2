@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Orders</h1>
    @if(! auth()->user()->isAdmin())
        <a href="{{ route('orders.create') }}" class="btn btn-primary mb-3">Add Order</a>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Order Date</th>
                <th>Total Amount</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->customer->name }}</td>
                <td>{{ $order->order_date }}</td>
                <td>{{ $order->total_amount }}</td>
                <td>
                    <a href="{{ route('orders.show', $order) }}" class="btn btn-info btn-sm">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
