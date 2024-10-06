<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
class LongController extends Controller
{
    public function nearby(Request $request)
    {
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $radius = $request->input('radius', 10); // Default radius 10 km
        // Validate the input
        if (is_null($latitude) || is_null($longitude)) {
            return response()->json(['error' => 'Latitude and Longitude are required.'], 400);
        }
        // Query to filter users by distance using Eloquent
        $users = User::selectRaw("id, name, latitude, longitude,
            ( 6371 * acos(
                cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) +
                sin(radians(?)) * sin(radians(latitude))
            ) ) AS distance",
            [$latitude, $longitude, $latitude])
            ->havingRaw('distance < ?', [$radius]) // Use havingRaw for distance condition
            ->orderBy('distance', 'asc') // Optional: Order users by distance
            ->get();
        return response()->json($users);
    }
}
