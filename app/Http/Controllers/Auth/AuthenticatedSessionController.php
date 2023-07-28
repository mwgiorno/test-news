<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        $user = Auth::user();

        return response()->json([
                'data' => [
                    'token' => $user->createToken('auth_token')
                                    ->plainTextToken
                ]
            ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): void
    {
        $request->user()->currentAccessToken()->delete();
    }
}
