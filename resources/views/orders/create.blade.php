@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Add Order</h1>
    <form action="{{ route('orders.store') }}" method="POST">
        @csrf
        <input type="hidden" name="customer_id" value="{{ auth()->user()->customer_id }}">
        <div class="mb-3">
            <label for="order_date" class="form-label">Order Date</label>
            <input type="date" name="order_date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="total_amount" class="form-label">Total Amount</label>
            <input type="number" name="total_amount" class="form-control" step="0.01" required>
        </div>
        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
