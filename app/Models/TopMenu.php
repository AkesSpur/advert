<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TopMenu extends Model
{
    protected $fillable = [
        'all_accounts',
        'has_video',
        'new',
        'vip',
        'cheapest',
        'verified',
        'status',
        'name'
    ];

    protected $casts = [
        'all_accounts' => 'boolean',
        'has_video' => 'boolean',
        'new' => 'boolean',
        'vip' => 'boolean',
        'cheapest' => 'boolean',
        'verified' => 'boolean',
       'status' => 'boolean',
    ];
}
