<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecoverPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SignInRequest;
use App\Http\Requests\SignUpRequest;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    )
    {
    }

    public function signUp(SignUpRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password_hash' => Hash::make($data['password']),
        ]);

        Auth::login($user);

        $request->session()->regenerate();

        return response()->json($user, 201);
    }

    public function signIn(SignInRequest $request)
    {
        $data = $request->validated();

        if (!Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            return response()->json([], 401);
        }

        $request->session()->regenerate();

        return response()->json(Auth::user());
    }

    public function sendResetPasswordLink(RecoverPasswordRequest $request)
    {
        $data = $request->validated();

        $email = $data['email'];

        $user = User::where('email', $email)->first();

        if ($user) {
            $emailKey = "password-reset:email:{$email}";

            $existingToken = Redis::get($emailKey);

            if (!$existingToken) {
                $token = (string)Str::uuid();

                $this->authService->sendResetPasswordLink(
                    $user->username,
                    $email,
                    $token
                );

                Redis::setex($emailKey, 300, $token);

                Redis::setex("password-reset:token:{$token}", 300, $email);
            }
        }

        return response()->json();
    }

    public function resetPassword(ResetPasswordRequest $request, string $token)
    {
        $data = $request->validated();

        $tokenKey = "password-reset:token:$token";

        $email = Redis::get($tokenKey);

        if (!$email) {
            return response()->json(['message' => 'Token has expired',], 401);
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['message' => 'Token has expired',], 401);
        }

        $user->update(['password_hash' => Hash::make($data['password']),]);

        Redis::del($tokenKey, "password-reset:email:{$email}");

        return response()->json();
    }
}
