<?php
namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Notifications\MessageNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function sendNotification(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'sender_id' => 'required|integer',
            'receiver_id' => 'required|integer',
            'receiver_name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

       

        // Create the message
        $message = Message::create([
            'sender_id' => $request->sender_id,
            'receiver_id' => $request->receiver_id,
            'receiver_name' => $request->receiver_name, 
            'title' => $request->title,
            'description' => $request->description,
        ]);

        // Find the receiver user by ID
        $receiver = User::find($request->receiver_id);


        // Check if the receiver exists
        if ($receiver) {
            // Send the notification
            $receiver->notify(new MessageNotification($message));
            return response()->json(['message' => 'Notification sent successfully.']);
        } else {
            // Return an error response if the receiver is not found
            return response()->json(['error' => 'Receiver not found.'], 404);
        }
    }
}
