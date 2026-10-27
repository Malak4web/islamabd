<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public function login(LoginRequest $request)
    {
        // Log out any previous session to ensure clean switch between admin and data entry
        Auth::guard('web')->logout();

        if (!Auth::guard('web')->attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid credentials.'
            ], 401);
        }

        if ($request->hasSession()) {
            $request->session()->regenerate();
        }

        $user = Auth::guard('web')->user();

        return response()->json([
            'data' => $user,
            'message' => 'Logged in successfully.'
        ]);
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return response()->json([
            'message' => 'Logged out successfully.'
        ]);
    }

    /**
     * Get the authenticated user.
     */
    public function user(Request $request)
    {
        $user = Auth::guard('web')->user() ?? $request->user();

        return response()->json([
            'data' => $user
        ]);
    }
}
