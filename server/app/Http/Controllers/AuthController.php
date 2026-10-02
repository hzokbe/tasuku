<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignInRequest;
use App\Http\Requests\SignUpRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
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
}
