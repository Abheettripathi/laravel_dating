<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Regional_group;
use Illuminate\Support\Facades\Storage;

class Regional_GroupController extends Controller
{
   
    public function index()
    {
        
        return Regional_group::all();   // Retrieve and return all records from the Regional_group model
    }

    
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'group_image' => 'required|file|mimes:jpeg,png,jpg,gif|max:2048', // Validate that group_image is a required file with specific mime types and a max size of 2048KB
            'country_id' => 'required|integer', // Validate that country_id is a required integer
            'state_id' => 'required|integer', // Validate that state_id is a required integer
            'group_name' => 'required|string', // Validate that group_name is a required string
            'user_id' => 'required|integer', // Validate that user_id is a required integer
            'is_block' => 'required|boolean' // Validate that is_block is a required boolean
        ]);

        // Get all validated data from the request
        $data = $request->all();

        // Handle file upload for group_image
        if ($request->hasFile('group_image')) {
            // Store the uploaded file in the 'uploads' directory within the 'public' disk
            $data['group_image'] = $request->file('group_image')->store('uploads', 'public');
        }

        // Create a new Regional_group record with the validated data
        $regionalGroup = Regional_group::create($data);

        // Return a JSON response indicating success and include the created regional group data
        return response()->json([
            'message' => 'Regional group created successfully',
            'data' => $regionalGroup,
        ], 201); // 201 status code indicates resource creation
    }

   
    public function show($id)
    {
       
        return Regional_group::findOrFail($id);
    }

    
    public function update(Request $request, $id)
    {
       
        $regionalGroup = Regional_group::findOrFail($id);

       
        $request->validate([
            'group_image' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
            'country_id' => 'required|integer', 
            'state_id' => 'required|integer', 
            'group_name' => 'required|string', 
            'user_id' => 'required|integer', 
            'is_block' => 'required|boolean' 
        ]);

        
        $data = $request->except('group_image');

        // Handle file upload for group_image
        if ($request->hasFile('group_image')) {
           
            if ($regionalGroup->group_image) {        // Delete the old image if it exists
                Storage::disk('public')->delete($regionalGroup->group_image);
            }
            $data['group_image'] = $request->file('group_image')->store('uploads', 'public');            // Store the new uploaded file in the 'uploads' directory within the 'public' disk
        }

        
        $regionalGroup->update($data);
        return response()->json([ 
            'message' => 'Regional group updated successfully',
            'data' => $regionalGroup,
        ], 200); 
    }
    public function destroy($id)  
    {
      $regionalGroup = Regional_group::findOrFail($id);  
        $regionalGroup->delete(); 
        return response()->json([   
            'message' => 'Data deleted successfully !!',
        ], 204); 
    }
}
