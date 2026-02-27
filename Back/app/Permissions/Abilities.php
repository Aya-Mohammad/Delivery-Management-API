<?php

namespace App\Permissions;
use App\Models\User;

final class Abilities{
    public const ADMIN='admin';
    public const USER='user';
    public const DRIVER='driver';
    public static function getAbilities($type)
    {
        if($type == 'A')return [self::ADMIN];
        else if($type == 'D')return [self::DRIVER];
        else return [self::USER];
    }
   
}