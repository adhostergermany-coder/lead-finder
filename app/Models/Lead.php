<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'company_name',
        'category',
        'email',
        'phone',
        'website',
        'address',
        'area',
        'lat',
        'lon',
        'source',
        'rating',
        'total_ratings',
        'website_quality',
    ];
}
