<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ListController;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Http\Request;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response('Hello, world!', 200);
});

Route::middleware([
    EncryptCookies::class,
    AddQueuedCookiesToResponse::class,
    StartSession::class,
])->group(function () {
    Route::post('sign-up', [AuthController::class, 'signUp']);

    Route::post('sign-in', [AuthController::class, 'signIn']);

    Route::post('recover-password', [AuthController::class, 'sendResetPasswordLink']);

    Route::post('reset-password/{token}', [AuthController::class, 'resetPassword']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('me', function () {
        return Auth::user();
    });

    Route::post('sign-out', function (Request $request) {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    });

    Route::get('lists', [ListController::class, 'getAll']);

    Route::post('lists', [ListController::class, 'create']);
});
