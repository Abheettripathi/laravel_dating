<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

namespace App\Http\Controllers;

use App\Models\Interests;
use Illuminate\Http\Request;

class InterestsController extends Controller
{
    
    public function index()
    {
        return Interests::all();
    }

    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'image' => 'required|string|max:255',
            'name' => 'required|string|max:255',
        ]);

        $interest = Interests::create($validatedData);

        return response()->json($interest, 201);
    }

    
    public function show($id)
    {
        $interest = Interests::find($id);

        // if (!$interest) {
        //     return response()->json(['message' => 'Interest not found'], 404);
        // }

        // return response()->json($interest);
        // */
    }

    
    public function update(Request $request, $id)
    {
        $interest = Interests::find($id);

        if (!$interest) {
            return response()->json(['message' => 'Interest not found'], 404);
        }

        $validatedData = $request->validate([
            'image' => 'sometimes|required|string|max:255',
            'name' => 'sometimes|required|string|max:255',
        ]);

        $interest->update($validatedData);

        return response()->json($interest);
    }

   
    public function destroy($id)
    {
        $interest = Interests::find($id);

        if (!$interest) {
            return response()->json(['message' => 'Interest not found'], 404);
        }

        $interest->delete();

        return response()->json(['message' => 'Interest deleted']);
    }
}


