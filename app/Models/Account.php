<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;


class Account extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['username', 'password', 'customer_id', 'is_admin'];

    protected $hidden = ['password'];

    protected $casts = [
        'is_admin' => 'boolean',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    public function getAuthPasswordName(): string
    {
        return 'password';
    }
}
