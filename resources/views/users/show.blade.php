@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0">My Account</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <strong>Username:</strong> {{ $account->username }}
            </div>
            <div class="mb-3">
                <strong>Email:</strong> {{ $account->customer?->email ?? 'No linked customer email' }}
            </div>
            <div class="mb-0">
                <strong>Role:</strong> {{ $account->is_admin ? 'Admin' : 'User' }}
            </div>
        </div>
    </div>

    @if(! $account->is_admin)
        <form action="{{ route('account.destroy') }}" method="POST" class="mt-3">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete your account?')">Delete Account</button>
        </form>
    @endif
</div>
@endsection
