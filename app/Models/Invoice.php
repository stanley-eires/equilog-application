<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = [
        'user_id', 'invoice_ref', 'items', 'status', 'date_approved', 'handler', 'amount', 'payment_status', 'date_paid'
    ];

    protected $casts = [
        'items' => 'array',
    ];
}
