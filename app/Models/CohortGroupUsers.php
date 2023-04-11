<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CohortGroupUsers extends Model
{
    use HasFactory;
    protected $fillable = ['cohort_group_id', 'user_id'];
}
