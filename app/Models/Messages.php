<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'sender_id', 'receiver_id', 'subject', 'message', 'read_at', 'sender_deleted_at', 'receiver_deleted_at'
    ];
}
