@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Products</h1>
    @if(auth()->user()->isAdmin())
        <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">Add Product</a>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                @if(auth()->user()->isAdmin())
                    <th>Actions</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->price }}</td>
                @if(auth()->user()->isAdmin())
                    <td>
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
