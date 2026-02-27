<?php

namespace App\Traits;

use PHPUnit\Framework\Attributes\UsesFunction;
use App\Models\Location;
use Exception;
trait LocationFunctions
{

    protected function location($attributes)
    {
        if (is_null($attributes)) {
            return null;
        }
        try{
            $location = Location::whereAll(
                ['longitude', 'latitude', 'city', 'street'],
                $attributes
            )->get()[0];
        }catch(Exception $e){
            $location = Location::create($attributes);
        }
        return $location->id;
    }
}