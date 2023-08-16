<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    public $incrementing = false;

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $guarded = [];

    protected $casts = [
        'id' => 'string',
        'createdAt' => 'timestamp'
    ];

    protected $hidden = ['updatedAt'];

    public static function boot() {
        parent::boot();
        static::creating(function ($query) {
            $query->id = Str::uuid()->toString();
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'categoryId', 'id');
    }
}
