<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class CustomerAccount extends Model
{
    use HasFactory;

    protected $fillable = ['username', 'password', 'customer_id'];

    public function customer() 
    {
        return $this->belongsTo(Customer::class);
    }
}
