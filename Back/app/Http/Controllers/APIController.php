<?php

namespace App\Http\Controllers;

use App\Services\ImagesService;
use App\Traits\ImagesFunctions;
use App\Traits\LocationFunctions;
use App\Traits\ProductsFunctions;
use App\Traits\Responses;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
class APIController extends Controller
{
    use Responses;
    use ImagesFunctions;
    use LocationFunctions;
    use ProductsFunctions;

    public function isAble($ability, $targetModel){
        return $this->authorize($ability, $targetModel);
    }

    public function checkUserID($request){
        if ($request->hasHeader('Authorization')) {
            $token = str_replace('Bearer ', '', $request->header('Authorization'));
            $accessToken = PersonalAccessToken::findToken($token);
            if ($accessToken) {
                Auth::setUser($accessToken->tokenable);
            }
        }
        if (!Auth::user())
            $user_id = 0;
        else
            $user_id = Auth::user()->id;
        return $user_id;
    }
}
