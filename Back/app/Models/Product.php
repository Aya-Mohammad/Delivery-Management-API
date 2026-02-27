<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Shop;
use App\Models\BreakTable;


class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'arabic_name',
        'category_id',
        'shop_id',
        'description',
        'arabic_description',
        'quantity',
        'price',
        'descount'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'product_id');
    }

}