<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlockController extends Controller
{
    public function blockUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = Auth::user();
        $blockedUser = User::find($request->user_id);

        if ($blockedUser) {
            $blockedUser->is_block = '1';
            $blockedUser->save();

            return response()->json([
                'message' => 'User blocked successfully'
            ], 200);
        } else {
            // If the user is not found, return a 'User not found' response
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }
    }

    public function unblockUser(Request $request)
    {
         
        $request->validate([
            'user_id' => 'required|exists:users,id', // Ensure 'user_id' is present and exists in the 'users' table
        ]);
    
        
        $user = Auth::user(); // Fetch the currently logged-in user using Laravel's Auth facade
    
        // Step 3: Prevent the user from unblocking themselves
        if ($user->id == $request->user_id) {
            // If the logged-in user's ID matches the 'user_id' they are trying to unblock, deny the action
            return response()->json([
                'message' => 'You cannot unblock yourself.' // Send an error message indicating that the user can't unblock themselves
            ], 400); // Return an HTTP 400 Bad Request status code
        }
    
        
        $blockedUser = User::find($request->user_id); 
    
       
        if ($blockedUser) {
            // Step 6: Check if the user is already unblocked
            if ($blockedUser->is_block == '0') {
                
                return response()->json([
                    'message' => 'User is already unblocked.' // Inform that the user is already unblocked
                ], 200); // Return an HTTP 200 OK status code
            }
    
            // Step 7: Unblock the user by setting the 'is_block' field to '0'
            $blockedUser->is_block = '0'; // Set the 'is_block' field to 0 (unblock the user)
            $blockedUser->save(); // Save the changes to the database
    
         
            return response()->json([
                'message' => 'User unblocked successfully.' // Inform that the user was unblocked successfully
            ], 200); // Return an HTTP 200 OK status code
        } 
        else {
            // Step 9: If the user does not exist, return a 'User not found' message
            return response()->json([
                'message' => 'User not found.' // Send a response that the user does not exist
            ], 404); // Return an HTTP 404 Not Found status code
        }
    }
    
    public function checkuser(Request $request)
    {
        
        $request->validate([
            'user_id' => 'required|exists:users,id', // Ensure 'user_id' is present and exists in the 'users' table
        ]);
    
        
        $user = Auth::user(); // Fetch the currently logged-in user using Laravel's Auth facade
    
      
        $blockedUser = User::find($request->user_id); // Retrieve the user record for the provided 'user_id'
    
        
        if ($blockedUser) {
            // Step 5: Determine whether the user is blocked or not
            $status = $blockedUser->is_block == '1' ? 'blocked' : 'unblocked'; // If 'is_block' is 1, user is blocked; otherwise, they are unblocked
    
           
            return response()->json([
                'message' => 'User is ' . $status, // Return a message with the user's status (blocked or unblocked)
                'status' => $status // Return the status in the response (blocked/unblocked)
            ], 200); // Return an HTTP 200 OK status code
        } 
        else {
            
            return response()->json([
                'message' => 'User not found' // User not found in the database
            ], 404); // Return an HTTP 404 Not Found status code
        }
    }
    
}