<?php
namespace App\Http\Controllers;

use App\Mail\PasswordResetMail;
use App\Mail\WelcomeEmail;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    public function requestReset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $token = Str::random(60);  //Generates a secure, random token
        $link = url("newpassword/".$token);    //Creates a complete URL that includes the token, which is used for password reset purposes. This URL is then sent to the user via email.

        PasswordReset::create([
            'email' => $request->email,//The email address of the user requesting the password reset.
            'token' => Hash::make($token), // A securely hashed version of the randomly generated token.
            
            'created_at' => now(),
        ]);

        // Send email with the reset link
        Mail::to($request->email)->send(new PasswordResetMail($link));

        return response()->json(['message' => 'Password reset link has been sent to your email.']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $passwordReset = PasswordReset::where('email', $request->email)->first();

        if (!$passwordReset || !Hash::check($request->token, $passwordReset->token)) {
            return response()->json([
                'message' => 'Invalid token or email'
            ], 400);
        }

        $user = User::where('email', $request->email)->first();
        $user->update(['password' => Hash::make($request->password)]);

        PasswordReset::where('email', $request->email)->delete();

        return response()->json([
            'message' => 'Password reset successfully'
        ], 200);
    }
}
