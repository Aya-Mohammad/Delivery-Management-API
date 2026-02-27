<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\EmailLoginUserRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Requests\Auth\NumberLoginUserRequest;
use App\Http\Requests\Auth\ResendCodeRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\VerifyAccountRequest;
use App\Http\Requests\Auth\ResetPasswordCodeRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Permissions\Abilities;
use App\Notifications\EmailVerificationNotification;

class AuthController extends APIController
{
    protected $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }
    public function numberLogin(NumberLoginUserRequest $request)
    {
        $attributes = $request->validate($request->rules());

        if (!Auth::attempt($attributes)) {
            return $this->error(__("Invalid credentials"), 0);
        }

        $data = $this->service->numberLogin($attributes['number'], $request->fcm_token);
        
        return $this->ok(
            __("login succesfully"),
            $data
        );
    }

    public function emailLogin(EmailLoginUserRequest $request)
    {
        $attributes = $request->validate($request->rules());

        if (!Auth::attempt($attributes)) {
            return $this->error(__("Invalid credentials"), 0);
        }
        $data = $this->service->emailLogin($attributes['email'], $request->fcm_token);
        return $this->ok(
            __("login succesfully"),
            $data
        );
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        $this->service->logout($request->user()->id);
        return $this->message(__("logout succesfully"));
    }
    public function register(RegisterUserRequest $request)
    {
        $attributes = $request->validate($request->rules());
        $data = $this->service->register($attributes);
        
        return $this->ok(
            __("account created successfully"),
            $data
        );
    }
    public function resendCode(ResendCodeRequest $request)
    {
        $this->service->resendCode($request->user()->id);
        return $this->message(__('a new verification code is sent'));
    }
    public function verify(VerifyAccountRequest $request)
    {
        if ($request->otp == $request->user()->otp) {
            $data = $this->service->verify($request->user()->id);
            return $this->ok(
                __('account verified successfully'),
                $data
            );
        } else {
            return $this->message(
                __('Invalid OTP'),
                0
            );
        }
    }
    public function resetPasswordCode(ResetPasswordCodeRequest $request)
    {
        $this->service->resetPasswordCode($request->email);
        return $this->message(__('a verification code is sent'));
    }
    public function resetPassword(ResetPasswordRequest $request)
    {
        $data = $this->service->resetPassword($request->email, $request->otp, $request->password);
        return $this->message($data['message'], $data['status']);
    }
}
