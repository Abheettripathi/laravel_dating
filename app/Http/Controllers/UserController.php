<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Interests;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;
use Carbon\Carbon;

class UserController extends Controller
{
    // Filter users based on various criteria such as gender, date, country, city, interest, and religion
    public function filterUsers(Request $request)
    {
        $query = User::query(); // Initialize the query builder for User model

        // Filter by gender if provided in the request
        if ($request->filled('gender')) {
            $query->where('gender', $request->input('gender'));
        }

        // Filter by created_at date if provided in the request
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->input('date'));
        }

        // Filter by country_id if provided
        $countryFilter = $request->input('country_id');
        $query->when($countryFilter, function($query, $country) {
            return $query->where('country', $country);
        });

        // Filter by city_id if provided, using relationship to City model
        if ($request->filled('city_id')) {
            $query->whereHas('city', function ($q) use ($request) {
                $q->where('id', $request->input('city_id'));
            });
        }

        // Filter by interest_id if provided, using relationship to Interests model
        if ($request->filled('interest_id')) {
            $query->whereHas('interest', function ($q) use ($request) {
                $q->where('id', $request->input('interest_id'));
            });
        }

        // Filter by religion_id if provided, using relationship to Religion model
        if ($request->filled('religion_id')) {
            $query->whereHas('religion', function ($q) use ($request) {
                $q->where('id', $request->input('religion_id'));
            });
        }

        // Get all the filtered users along with their related data (country, city, interest, religion)
        $users = $query->with(['country', 'city', 'interest', 'religion'])->get();

        // Return the filtered users as a JSON response
        return response()->json($users);
    }

    // Function to send a welcome email to the user
    public function email_send(Request $request)
    {
        // Validate that the email field is present and is a valid email format
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            $to = $request->input('email'); // Get the email from the request
            $msg = "verify the email links"; // Define the message
            $subject = "code step by step"; // Define the subject of the email

            // Send the email using the WelcomeEmail Mailable
            Mail::to($to)->send(new WelcomeEmail($msg, $subject));

            // Return a success message if email is sent successfully
            return response()->json([
                'success' => true,
                'message' => 'Email sent successfully'
            ], 200);
        } catch (Exception $e) {
            // Return an error message if the email sending fails
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Filter users into different age groups
    public function filterByAgeGroups1(Request $request)
    {
        $users = User::all(); // Get all users from the database

        // Filter users who are 18 years old or younger
        $younger = $users->filter(function ($user) {
            return Carbon::parse($user->date_of_birth)->age <= 18;
        });

        // Filter users who are between 19 and 25 years old
        $youngAdult = $users->filter(function ($user) {
            return Carbon::parse($user->date_of_birth)->age >= 19 && Carbon::parse($user->date_of_birth)->age <= 25;
        });

        // Filter users who are between 26 and 35 years old
        $adult = $users->filter(function ($user) {
            return Carbon::parse($user->date_of_birth)->age >= 26 && Carbon::parse($user->date_of_birth)->age <= 35;
        });

        // Return the filtered users in the different age groups
        return response()->json([
            'younger_0_18' => $younger,
            'young_adult_19_25' => $youngAdult,
            'adult_26_35' => $adult,
        ]);
    }

    // Filter users who are 18 years old or younger using a raw SQL query
    public function filterByAgeGroups2(Request $request)
    {
        $today = Carbon::today(); // Get today's date

        // Use raw SQL to filter users whose age is 18 or younger
        $younger = User::whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, ?) <= 18', [$today])->get();

        // Return the filtered users in the 0-18 age group
        return response()->json([
            'younger_0_18' => $younger,
        ]);
    }

    // Get users based on specific ages provided in the query string (e.g., ?ages=18,25,30)
    public function getUsersByAges(Request $request)
    {
        $today = Carbon::today(); // Get today's date

        $ages = $request->query('ages', ''); // Get the 'ages' parameter from the query string

        // Convert the 'ages' string into an array of integers
        $agesArray = array_map('intval', explode(',', $ages));

        // Initialize the query builder
        $query = User::query();

        // For each age in the array, filter the users whose age matches the given age
        foreach ($agesArray as $age) {
            $query->orWhereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, ?) = ?', [$today, $age]);
        }

        // Get the filtered users
        $filteredUsers = $query->get();

        // Return the users as a JSON response
        return response()->json([
            'users' => $filteredUsers,
        ]);
    }

    // Get the total number of users in the system
    public function getUserCount()
    {
        $userCount = User::count(); // Count all users
        return response()->json([
            'total_users' => $userCount
        ]);
    }

    // Get the count of users excluding the logged-in user and blocked users
    public function getUserCount1()
    {
        $authUser = auth()->user(); // Get the currently authenticated user

        // Get the IDs of users blocked by the authenticated user
        $blockedUserIds = $authUser->blockedUsers()->pluck('blocked_user_id');

        // Count users excluding the logged-in user and blocked users
        $userCount = User::where('id', '!=', $authUser->id) // Exclude the logged-in user
                         ->whereNotIn('id', $blockedUserIds) // Exclude blocked users
                         ->count();

        // Return the count of users as a JSON response
        return response()->json([
            'total_users' => $userCount
        ]);
    }

    // Get users who are not blocked (is_block is not set to 1)
    public function getUsersExcludingBlocked()
    {
        // Fetch users who are not blocked (where 'is_block' is 0)
        $users = User::where('is_block', 0)->get();

        // Return the users as a JSON response
        return response()->json([
            'users' => $users
        ]);
    }
}
