<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UpadateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function storeOrUpdate(Request $request)
    {
        $authUser = Auth::user();

        // Validate the request input
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $request->id,
            'password' => 'sometimes|string|min:8|confirmed',
            'state' => 'required|integer',  // Assuming state is an integer (ID)
            'city' => 'required|integer',   // Assuming city is an integer (ID)
            'phone' => 'required|string|max:20',
            'gender' => 'required|string|max:20',
            'country' => 'required|integer', // Assuming country is an integer (ID)
            'tribes' => 'required|integer',  // Assuming tribes is an integer (ID)
            'looking_for' => 'required|string|max:255',
            'religion' => 'required|integer', // Assuming religion is an integer (ID)
            'qualifications' => 'required|string|max:255',
            'about' => 'required|string|max:1000',
            'interest' => 'required|integer',
            'lat' => 'required|numeric',
            'lang' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Find the user by ID
        $user = User::find($request->id);

        
        if ($request->filled('id')) {
            $user->id = Hash::make($request->input('id'));
        }

        // Check if password needs to be updated
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        // Update user profile fields
        $user->fill($request->except(['password', 'email']));

        // Update the email only if it has changed
        if ($request->input('email') !== $user->email) {
            $user->email = $request->input('email');
        }

        // Save the user profile
        $user->save();

        // Return a success response
        return response()->json([
            'message' => 'Profile saved successfully',
            'user' => $user
        ]);
    }
}
