@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Product Details</h1>
    <div class="mb-3">
        <strong>Name:</strong> {{ $product->product_name }}
    </div>
    <div class="mb-3">
        <strong>Price:</strong> {{ $product->price }}
    </div>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection