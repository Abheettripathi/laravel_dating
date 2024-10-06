<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OTP;
use Illuminate\Support\Facades\Validator;
//use Illuminate\Support\Facades\DB;

class OTPController extends Controller
{
    public function sendOTP(Request $request)
    {
        // Validate the request input
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'country_code' => 'required'
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Extract phone and country code from the request
        $mobile = $request->input('phone'); // Ensure 'phone' is used consistently
        $countryCode = $request->input('country_code');
        $otp = rand(100000, 999999);
         // Generate or assign an OTP here

        // Check if the phone number already exists in the OTP table
        // $user = OTP::where('mobile', $mobile)->first();
        // if ($user) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'The number is already in use',
        //         'status' => 409
        //     ], 409);
        OTP::updateOrCreate(
            ['mobile' => $mobile],
            [
                'country_code' => $countryCode,
                'otp' => $otp,
                
            ]
        );

        // Insert or update the OTP record in the database
        // DB::table('otps')->updateOrInsert(
        //     ['mobile' => $mobile],
        //     [
        //         'country_code' => $countryCode,
        //         'otp' => $otp,
        //     ]
        // );

        // Return a successful response
        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully',
            'data' => [
                'mobile' => $mobile,
            ],
            'status' => 200
        ], 200);
    }
    public function verifyOTP(Request $request)
    {
        // Validate the request input
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'otp' => 'required',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Extract phone and OTP from the request
        $mobile = $request->input('phone');
        $otp = $request->input('otp');

        // Check if the OTP and mobile number match
         $userOTP = OTP::where('mobile', $mobile)->where('otp', $otp)->first();

        if ($userOTP) {
            // Optionally delete the OTP or mark it as verified
            // $userOTP->delete(); // Uncomment to delete after successful verification

            return response()->json([
                'success' => true,
                'message' => 'OTP verified successfully',
                'status' => 200
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP or mobile number',
                'status' => 400
            ], 400);
        }
}
}