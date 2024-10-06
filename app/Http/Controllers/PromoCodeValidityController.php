<?php

namespace App\Http\Controllers;
use App\Models\PromoCode;
use Illuminate\Http\Request;

class PromoCodeValidityController extends Controller

{
    public function createPromoCode(Request $request)
    {
        
        $request->validate([
            'code' => 'required|string|unique:promo_codes,code',
            'type' => 'required|in:1,2',// the promo code, such as a fixed discount (1) or a percentage discount (2).
            'code_for' => 'required|in:1,2', //the promo code, such as for a specific user type (1) or a specific product category (2).
            'discount_amount' => 'nullable|numeric',
            'upto_discount_amount' => 'nullable|numeric',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after:from_date',//his ensures that the end date of the promo code validity is after the start date.
        ]);
    
        
        $promoCode = PromoCode::create($request->all());
    
        return response()->json([
            'message' => 'Promo code created successfully',
            'data' => $promoCode
        ], 201);
    }

    public function checkPromoCodeValidity(Request $request)
    {
        // Validate the request to ensure 'code' is provided and is a string
        $request->validate([
            'code' => 'required|string',
        ]);
        // Retrieve the promo code from the request

        $code = $request->input('code');

        //// Find the promo code that matches the provided code and has not expired
        $promoCode = PromoCode::where('code', $code)
            ->where('to_date', '>=', now())
            ->first();


// Check if the promo code exists and is valid
        if ($promoCode) {
            return response()->json([
                'message' => 'Promo code is valid',
                'data' => $promoCode
            ]);
        } else {
            return response()->json([
                'message' => 'Promo code is invalid or expired'
            ], 404);
        }
    }
    
    

}

