<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;

class CityController extends Controller
{
  
    public function index()
    {
        return City::all();
    }

    
    public function store(Request $request)
    {
        $request->validate([
        
            'city'=>'required|string|max:255',
            'state_id'=>'required|integer',
        ]);
        return City::create($request->all());
    }

    
    public function show($id)
    {
        return City::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $cities=City::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'state_id' => 'required|integer',
        ]);
        $cities->update($request->all());
        return $cities;
    }

   
    public function destroy($id)
    {
        $city=City::findOrFail($id);
        $city->delete();
        return response()->json(['message'=>'data deleted successfully!'] , 204);
    }
}
