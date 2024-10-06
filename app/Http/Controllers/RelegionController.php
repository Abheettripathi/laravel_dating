<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Religion;

class RelegionController extends Controller
{
    
    public function index()
    {
        return Religion::all();
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required| string|max:255',]);

            
            return Religion::create($request->all());
    }

    
    public function show($id)
    {
        return Religion::findOrFail($id);
    }

    
    public function update(Request $request, $id)
    {
        $religion=Religion::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',]);
        $religion->update($request->all());
        return $religion;
    }

    
    public function destroy($id)
    {
       // return response()->json(['message' => 'dfd'], 400);

      //  return $id;
        // $religion=Religion::findOrFail($id);
        // $religion->delete();
        return response()->json(['message' =>'data deleted successfully!'] , 204);
    }
}
