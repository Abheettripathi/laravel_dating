<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;

class RoomController extends Controller
{
    /**
     * Create a new room.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'auth_user_id' => 'required|exists:users,id',
            'challenge_user_id' => 'required|exists:users,id',
        ]);

        // Create a new room with a unique room number
        $room = Room::create([
            'room_number' => uniqid(),
            'auth_user_id' => $validatedData['auth_user_id'],
            'challenge_user_id' => $validatedData['challenge_user_id'],
        ]);

        // Return the created room as a JSON response with a 201 status code
        return response()->json($room, 201);
    }
}
