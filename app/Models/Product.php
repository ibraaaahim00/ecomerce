<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'title',
        'description',
        'price',
        'stock',
        'image',
        'is_featured',
        'category_id',   // ✅ كانت ناقصة — بدونها الـ category_id مش بتتحفظ
    ];

    protected $casts = [
        'price'       => 'float',
        'stock'       => 'integer',
        'is_featured' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
