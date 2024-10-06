<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PromoCode;

class PromoCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PromoCode::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255',
            'type' => 'required|in:1,2',
            'code_for' => 'required|in:1,2',
            'discount_amount' => 'nullable|integer',
            'upto_discount_amount' => 'nullable|integer',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date'

        ]);
        $promocode =PromoCode::create($request->all());
        return response()->json([
            'message' => 'Promo code created successfully',
            'data' => $promocode
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return PromoCode::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $promocode=PromoCode::findOrFail($id);
        $request->validate([
            'code' => 'required|string|max:255',
            'type' => 'required|in:1,2',
            'code_for' => 'required|in:1,2',
            'discount_amount' => 'nullable|integer',
            'upto_discount_amount' => 'nullable|integer',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date'

        ]);
        $promocode->update($request->all());
        return response()->json([
            'message' => 'Promo code updated successfully',
            'data' => $promocode
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $promocode= PromoCode::findOrFail($id);
        $promocode->delete();
        return response()->json([
            'message' => 'Promo code deleted successfully',
            
        ], 204);
    }
}
