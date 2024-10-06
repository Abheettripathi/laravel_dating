<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prompt;


class PromptController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Prompt::all();
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
        'country_id' => 'nullable|integer',
        'state_id' => 'nullable|integer',
        'prompt' => 'required|string|max:255', // Ensure this is required
    ]);

    $prompt = Prompt::create($request->all());

    return response()->json([
        'message' => 'Prompt created successfully',
        'data' => $prompt
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
        return Prompt::findOrFail($id);
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
        $prompt=Prompt::findOrFail($id);
        $request->validate([
            'country_id'=>'nullable|integer',
            'state_id'=>'nullable|integer',
            'prompt'=>'required|string|max:255'
        ]);
        $prompt->update($request->all());
        return response()->json([
            'message'=>'data updated successfully !!',
            'data'=> $prompt
            
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $prompt=Prompt::findOrFail($id);
        $prompt->delete();
        return  response()->json([
            'message'=>'data deleted successfully !!',
            
        ], 204);
    }
}
