<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroSectionOverride extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_type',
        'model_id',
        'title',
        'text_content',
        'image',
        'is_active',
    ];

    /**
     * Get the parent model (Service, CustomCategory, etc.).
     */
    public function model()
    {
        return $this->morphTo();
    }
}