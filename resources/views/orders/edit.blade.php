@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Edit Order</h1>
    <form action="{{ route('orders.update', $order) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="customer_id" class="form-label">Customer</label>
            <select name="customer_id" class="form-control" required>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}" @if($order->customer_id == $customer->id) selected @endif>{{ $customer->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="order_date" class="form-label">Order Date</label>
            <input type="date" name="order_date" class="form-control" value="{{ $order->order_date }}" required>
        </div>
        <div class="mb-3">
            <label for="total_amount" class="form-label">Total Amount</label>
            <input type="number" name="total_amount" class="form-control" value="{{ $order->total_amount }}" step="0.01" required>
        </div>
        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection