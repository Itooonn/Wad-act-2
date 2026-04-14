@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Customer Details</h1>
    <div class="mb-3">
        <strong>Name:</strong> {{ $customer->name }}
    </div>
    <div class="mb-3">
        <strong>Email:</strong> {{ $customer->email }}
    </div>
    <a href="{{ route('customers.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection