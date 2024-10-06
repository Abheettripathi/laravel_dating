<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\State;

class StateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

   
    public function index()
    {
        return State::all();
    }

   
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required| string|max:255',
            'country_id' => 'required|integer',
        ]);
        return State::create($request->all());

    }

    
    public function show($id)
    {
        return State::findOrFail($id);
    }

   
    public function update(Request $request, $id)
    {
        $state =State::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'country_id'=>'required|integer',
        ]);

        $state->update($request->all());
        return $state;

    }

   
    public function destroy($id)
    {
        $state =State::findOrFail($id);
        $state->delete();
        return response()->json(null, 204);
    }
}
