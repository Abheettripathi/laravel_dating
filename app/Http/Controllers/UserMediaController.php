<?php
namespace App\Http\Controllers;

use App\Models\UserMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserMediaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'media_file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:500000', // Validates image files
        ]);

        // Handle file upload
        if ($request->hasFile('media_file')) {
            $file = $request->file('media_file');
            $path = $file->store('uploads', 'public'); // Save to the 'public/uploads' directory

            // Save the media information to the database
            $userMedia = UserMedia::create([
                'user_id' => $request->user_id,
                'media_type' => $file->getClientOriginalExtension(), // Save the file extension as media_type
                'media_path' => $path,
            ]);

            return response()->json($userMedia, 201);
        }

        return response()->json(['error' => 'File upload failed'], 400);
    }
}
