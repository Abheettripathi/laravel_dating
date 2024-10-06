<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    // Retrieve all events
    public function index() {
        return Event::all();
    }

    // Store a new event
    public function store(Request $request) {
        // Validate the request
        $validatedData = $request->validate([
            'image' => 'required|image',
            'country_id' => 'required|integer',
            'state_id' => 'required|integer',
            'city_id' => 'required|integer',
            'event_name' => 'required|string|max:255',
            'contact_details' => 'required|string|max:255',
            'link' => 'required|url',
            'description' => 'required|string'
        ]);

        // Handle the image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $validatedData['image'] = $path;
        }

        // Create the event
        $event = Event::create($validatedData);

        // Return the created event as a JSON response
        return response()->json($event, 201);
    }

    public function show($id) {
        // Find the event by ID or throw a 404 error if not found
        $event = Event::findOrFail($id);
        
        // Return the event as a JSON response
        return response()->json($event);
    }
    public function update(Request $request ,$id){
        $event =Event::findOrFail($id);
        $validatedData=$request->validate([
            'image' => 'required|image',
            'country_id' => 'required|integer',
            'state_id' => 'required|integer',
            'city_id' => 'required|integer',
            'event_name' => 'required|string|max:255',
            'contact_details' => 'required|string|max:255',
            'link' => 'required|url',
            'description' => 'required|string'
        ]);
        $event->update($validatedData->all());
        return response()->json($event ,201);
    }
    public function delete($id){
        $event=Event::findOrFail($id);
        $event->delete();
        return response()->json(['message' => 'Data deleted successfully!'], 204);

    }
    public function index1(Request $request) {
        // Initialize the query builder for the Event model
        $query = Event::query();
    
        // // Apply filters conditionally
        $query->when($request->filled('country_id'), function ($q) use ($request) {
            $q->where('country_id', $request->country_id);
        });
    
        $query->when($request->filled('state_id'), function ($q) use ($request) {
            $q->where('state_id', $request->state_id);
        });
    
         $query->when($request->filled('city_id'), function ($q) use ($request) {
         $q->where('city_id', $request->city_id);
        });
    
        // // Get the filtered results
         $events = $query->get();
    
        // // Return the events as a JSON response
         return response()->json($events);
    
    }
    
}    
