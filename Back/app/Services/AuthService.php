<?php

namespace App\Services;
use App\Models\User;
use App\Permissions\Abilities;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Support\Facades\Hash;
class AuthService
{
    public function numberLogin($number, $fcm_token)
    {
        $user = User::with(['images', 'location'])
            ->firstWhere('number', $number);
        $user->fcm_token = $fcm_token;
        $user->save();
        $abilities = Abilities::getAbilities($user->type);
        $token = $user->createToken
        (
            'user token for ' . $user->first_name,
            $abilities,
            now()->addMonth()
        )->plainTextToken;
        return [
            'token' => $token,
            'user' => $user
        ];
    }

    public function emailLogin($email, $fcm_token)
    {
        $user = User::with(['images', 'location'])
            ->firstWhere('email', $email);
        $user->fcm_token = $fcm_token;
        $user->save();
        $abilities = Abilities::getAbilities($user->type);
        $token = $user->createToken
        (
            'user token for ' . $user->first_name,
            $abilities,
            now()->addMonth()
        )->plainTextToken;
        return [
            'token' => $token,
            'user' => $user
        ];
    }
    
    public function logout($id)
    {
        $user = User::find($id);
        $user->fcm_token = null;
        $user->save();
    }

    public function register($attributes)
    {
        $attributes['password'] = Hash::make($attributes['password']);
        $otp = Rand(10000, 99999);
        $attributes['otp'] = $otp;
        $user = User::create($attributes);

        $user->notify(new EmailVerificationNotification($otp));
        $abilities = Abilities::getAbilities($user);
        $token = $user->createToken
        (
            'user token for ' . $user->first_name,
            $abilities,
            now()->addMonth()
        )->plainTextToken;
        return [
            'token' => $token,
            'user' => $user
        ];
    }

    public function resendCode($id)
    {
        $otp = Rand(10000, 99999);
        $user = User::find($id);
        $user->otp = $otp;
        $user->save();
        $user->notify(new EmailVerificationNotification('code: ' . $otp));
    }

    public function verify($id)
    {
        $user = User::find($id);
        $user->email_verified_at = now();
        $user->otp = null;
        $user->save();
        return $user;
    }

    public function resetPasswordCode($email)
    {
        $otp = Rand(10000, 99999);
        $user = User::firstWhere('email', $email);
        $user->password_otp = $otp;
        $user->save();
        $user->notify(new EmailVerificationNotification('code: ' . $otp));
    }

    public function resetPassword($email, $otp, $password)
    {
        $user = User::firstWhere('email', $email);
        if ($user->password_otp != $otp) {
            return [
                'message' => __('invalid OTP'),
                'status' => 0
            ];
        }
        $user->password_otp = null;
        $user->password = Hash::make($password);
        $user->save();
        return [
            'message' => __('password updated successfully'),
            'status' => 200
        ];
    }
}