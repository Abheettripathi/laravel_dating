<?php
namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoginController extends Controller
{
    public function login(Request $request)
    {
         // Validate the request input
    $request->validate([
        'email' => 'required|string|email',
        'password' => 'required|string',
    ]);

    // Attempt to log in the user
    $credentials = $request->only('email', 'password');
    try {
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid login details'], 401);
        }
    } catch (JWTException $e) {
        return response()->json(['message' => 'Could not create token'], 500);
    }

    // Get the authenticated user
    $user = Auth::user();


    // Return a success response with the token
    return response()->json([
        'message' => 'Login successful',
        'token' => $token,
        'user' => $user,
    ]);
    }

    public function logout(Request $request)
    {
        // Log out the user
        Auth::logout();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
