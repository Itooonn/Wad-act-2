@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Order Details</h1>
    <div class="mb-3">
        <strong>Customer:</strong> {{ $order->customer->name }}
    </div>
    <div class="mb-3">
        <strong>Order Date:</strong> {{ $order->order_date }}
    </div>
    <div class="mb-3">
        <strong>Total Amount:</strong> {{ $order->total_amount }}
    </div>
    <h4>Products</h4>
    <ul>
        @foreach($order->products as $product)
            <li>{{ $product->product_name }} (x{{ $product->pivot->quantity }})</li>
        @endforeach
    </ul>
    <a href="{{ route('orders.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection