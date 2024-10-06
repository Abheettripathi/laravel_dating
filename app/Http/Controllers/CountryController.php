<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;

class CountryController extends Controller
{
    // Constructor to apply middleware
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        return Country::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'countryCode' => 'required|integer',
            'name' => 'required|string|max:255',
        ]);
        return Country::create($request->all());
    }

    public function show($id)
    {
        return Country::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $country = Country::findOrFail($id);
        $request->validate([
            'countryCode' => 'required|integer',
            'name' => 'required|string|max:255',
        ]);
        $country->update($request->all());
        return $country;
    }

    public function destroy($id)
    {
        $country = Country::findOrFail($id);
        $country->delete();
        return response()->json(['message' => 'Data deleted successfully!'], 204);
    }
}
