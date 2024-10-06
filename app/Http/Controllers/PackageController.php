<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Package; // Import the Package model
use Illuminate\Support\Facades\Validator;

class PackageController extends Controller
{
    // Get all packages
    public function index()
    {
        // Retrieve all packages from the database
        $packages = Package::all();
        // Return the packages as a JSON response
        return response()->json($packages);
    }
    // Create a new package
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'package_name' => 'required|string|max:255',
            'coin' => 'required|integer',
            'price' => 'required|numeric',
            'discount' => 'nullable|integer|min:0|max:100',
        ]);
        // Check if validation fails
        if ($validator->fails()) {
            // Return validation errors as a JSON response
            return response()->json(['error' => $validator->errors()], 422);
        }
        // Create a new package in the database
        $package = Package::create([
            'package_name' => $request->package_name,
            'coin' => $request->coin,
            'price' => $request->price,
            'discount' => $request->discount,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        // Return the created package as a JSON response with status 201 (created)
        return response()->json($package, 201);
    }
    // Get a single package by id
    public function show($id)
    {
        // Find the package by its ID
        $package = Package::find($id);

        // Check if the package was found
        if (!$package) {
            // Return a 404 error if not found
            return response()->json(['error' => 'Package not found'], 404);
        }
        // Return the found package as a JSON response
        return response()->json($package);
    }
    // Update a package
    public function update(Request $request, $id)
    {
        // Find the package by its ID
        $package = Package::find($id);
        // Check if the package was found
        if (!$package) {
            // Return a 404 error if not found
            return response()->json(['error' => 'Package not found'], 404);
        }
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'package_name' => 'sometimes|required|string|max:255',
            'coin' => 'sometimes|required|integer',
            'price' => 'sometimes|required|numeric',
            'discount' => 'nullable|integer|min:0|max:100',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            // Return validation errors as a JSON response
            return response()->json(['error' => $validator->errors()], 422);
        }
        // Update the package with the request data
        $package->update($request->all());
        // Set the updated_at timestamp
        $package->updated_at = now();
        // Save the updated package
        $package->save();
      // Return the updated package as a JSON response
        return response()->json($package);
    }
    // Delete a package
    public function destroy($id)
    {
        // Find the package by its ID
        $package = Package::find($id);

        // Check if the package was found
        if (!$package) {
            // Return a 404 error if not found
            return response()->json(['error' => 'Package not found'], 404);
        }
        // Delete the package
        $package->delete();
        // Return a success message as a JSON response
        return response()->json(['message' => 'Package deleted successfully']);
    }
    // Get packages within a price range
    public function filterByPrice(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'min_price' => 'required|numeric',
            'max_price' => 'required|numeric',
        ]);
        // Check if validation fails
        if ($validator->fails()) {
            // Return validation errors as a JSON response
            return response()->json(['error' => $validator->errors()], 422);
        }
        // Retrieve packages within the specified price range
        $packages = Package::whereBetween('price', [$request->min_price, $request->max_price])->get();
        // Return the filtered packages as a JSON response
        return response()->json($packages);
    }
    // Get all packages with a discount
    public function packagesWithDiscount()
    {
        // Retrieve all packages that have a discount greater than 0
        $packages = Package::where('discount', '>', 0)->get();
        // Return the discounted packages as a JSON response
        return response()->json($packages);
    }
    // Search packages by name
    public function searchByName(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'package_name' => 'required|string|max:255',
        ]);
        // Check if validation fails
        if ($validator->fails()) {
            // Return validation errors as a JSON response
            return response()->json(['error' => $validator->errors()], 422);
        }
        // Retrieve packages that match the search criteria
        $packages = Package::where('package_name', 'like', '%' . $request->package_name . '%')->get();
        // Return the found packages as a JSON response
        return response()->json($packages);
    }
    // Get all packages with pagination
    public function indexPaginated(Request $request)
    {
        // Get the number of items per page from the request or default to 5
        $perPage = $request->get('per_page', 5);
        // Retrieve packages with pagination
        $packages = Package::paginate($perPage);
        // Return the paginated packages as a JSON response
        return response()->json($packages);
    }
    // Get the most expensive package
    public function mostExpensivePackage()
    {
        // Retrieve the package with the highest price
        $package = Package::orderBy('price', 'desc')->first();
        // Return the most expensive package as a JSON response
        return response()->json($package);
    }
    // Get the latest packages
    public function latestPackages(Request $request)
    {
        // Get the limit for the number of packages to retrieve from the request or default to 5
        $limit = $request->get('limit', 5);
        // Retrieve the latest packages based on creation date
        $packages = Package::orderBy('created_at', 'desc')->take($limit)->get();
        // Return the latest packages as a JSON response
        return response()->json($packages);
    }
    // Bulk delete packages
    public function bulkDelete(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array', // Ensure 'ids' is an array
            'ids.*' => 'integer|exists:coins,id', // Ensure each ID is an integer and exists in the coins table
        ]);
        // Check if validation fails
        if ($validator->fails()) {
            // Return validation errors as a JSON response
            return response()->json(['error' => $validator->errors()], 422);
        }
        // Delete the packages with the specified IDs
        Package::whereIn('id', $request->ids)->delete();
        // Return a success message as a JSON response
        return response()->json(['message' => 'Packages deleted successfully']);
    }
}
