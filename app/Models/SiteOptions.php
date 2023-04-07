<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteOptions extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    protected $casts = [
        'value' => 'array',
    ];

    public static function getOption($option)
    {
        return cache()->remember("option_{$option}", now()->addDays(7), fn () =>
        SiteOptions::select('value')->where('key', $option)->first()?->value);
    }
}
