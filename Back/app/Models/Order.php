<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Product;
use App\Models\Location;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'total_price',
        'location_id',
        'user_id',
        'pay_method',
        'driver_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function breakTable()
    {
        return $this->hasMany(BreakTable::class, 'order_id')
            ->orderBy(
                Product::select('shop_id')
                    ->whereColumn('products.id', 'break_tables.product_id')
            );
    }

    public function relations()
    {
        return $this->hasMany(BreakTable::class, 'order_id');
    }

    public function products(){
        return $this->hasManyThrough(Product::class, BreakTable::class,'order_id','id','id','product_id');
    }
}
