<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'arabic_name',
        'arabic_description',
        'location_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'shop_id');
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'shop_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
}
