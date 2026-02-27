<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'longitude',
        'latitude',
        'city',
        'street',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    public function users()
    {
        return $this->hasMany(User::class, 'location_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'location_id');
    }
}
