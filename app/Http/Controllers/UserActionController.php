<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserAction;
use App\Models\User; // Import the User model
use Illuminate\Http\JsonResponse;

class UserActionController extends Controller
{
    
    public function store(Request $request)
    {
        // Validate the request: target_user_id must exist in the users table, and action must be 'like' or 'dislike'
        $request->validate([
            'target_user_id' => 'required|exists:users,id',
            'action' => 'required|string|in:like,dislike',
        ]);

        // Update the user action if it exists, or create a new one if it doesn't
        $userAction = UserAction::updateOrCreate(
            [
                'user_id' => Auth::id(), // The authenticated user's ID
                'target_user_id' => $request->target_user_id, // Target user ID from the request
            ],
            [
                'action' => $request->action, // The action ('like' or 'dislike') from the request
            ]
        );

        // Return a success message along with the newly created or updated action
        return response()->json(['message' => 'Action recorded successfully!', 'userAction' => $userAction]);
    }

    // Fetch all users who liked the currently authenticated user
    public function getWhoLikedMe()
    {
        $authId = Auth::id(); // Get the ID of the currently authenticated user

        // Query for actions where the current user is the target and the action is 'like'
        $whoLikedMe = UserAction::where('target_user_id', $authId)
            ->where('action', 'like')
            ->get();

        // Return the list of users who liked the authenticated user
        return response()->json(['whoLikedMe' => $whoLikedMe]);
    }

    // Fetch mutual likes (users who liked each other)
    public function getMutualLikes()
    {
        $authId = Auth::id(); // Get the authenticated user's ID

        // Get a list of users the authenticated user has liked
        $likedUsers = UserAction::where('user_id', $authId)
            ->where('action', 'like')
            ->pluck('target_user_id'); // Get only the target user IDs

        // Query for mutual likes: users who liked the authenticated user and were liked back
        $mutualLikes = UserAction::whereIn('user_id', $likedUsers)
            ->where('target_user_id', $authId)
            ->where('action', 'like')
            ->get();

        // Return the list of mutual likes
        return response()->json(['mutualLikes' => $mutualLikes]);
    }

    // Fetch all like and dislike actions
    public function getAllLikesAndDislikes()
    {
        // Query for all actions where the action is either 'like' or 'dislike'
        $allActions = UserAction::whereIn('action', ['like', 'dislike'])->get();

        // Return the list of all actions
        return response()->json(['allActions' => $allActions]);
    }

    // Convert a 'like' action to a 'dislike' action
    public function convertLikeToDislike(Request $request)
    {
        $authId = Auth::id(); 

       
        $request->validate([
            'target_user_id' => 'required|exists:users,id',
        ]);

        $targetUserId = $request->target_user_id; // Get the target user ID from the request

        // Check if a 'like' action exists for the current user and target user
        $existingAction = UserAction::where('user_id', $authId)
            ->where('target_user_id', $targetUserId)
            ->where('action', 'like')
            ->first();

        // If a 'like' action exists, update it to 'dislike'
        if ($existingAction) {
            $existingAction->update(['action' => 'dislike']);

            // Return a success message with the updated action
            return response()->json(['message' => 'Like converted to dislike successfully!', 'userAction' => $existingAction]);
        } else {
            // If no 'like' action was found, return a 404 error
            return response()->json(['message' => 'No like found to convert to dislike.'], 404);
        }
    }

    // Remove mutual likes between the authenticated user and another user
    public function removeMutualLike(Request $request)
    {
        $authId = Auth::id(); // Get the authenticated user's ID

        // Validate the request: target_user_id must exist in the users table
        $request->validate([
            'target_user_id' => 'required|exists:users,id',
        ]);

        $targetUserId = $request->target_user_id; // Get the target user ID from the request

        // Check if the authenticated user liked the target user
        $authLikesTarget = UserAction::where('user_id', $authId)
            ->where('target_user_id', $targetUserId)
            ->where('action', 'like')
            ->first();

        // Check if the target user liked the authenticated user back
        $targetLikesAuth = UserAction::where('user_id', $targetUserId)
            ->where('target_user_id', $authId)
            ->where('action', 'like')
            ->first();

        // If both mutual likes exist, delete both actions
        if ($authLikesTarget && $targetLikesAuth) {
            $authLikesTarget->delete();
            $targetLikesAuth->delete();

            // Return a success message
            return response()->json(['message' => 'Mutual likes removed successfully!']);
        } else {
            // If no mutual likes are found, return a 404 error
            return response()->json(['message' => 'No mutual likes found to remove.'], 404);
        }
    }

    // Fetch users liked by the authenticated user but not disliked
    public function getFilteredUsers(): JsonResponse
    {
        $user = Auth::user(); // Get the authenticated user

        // Get the IDs of users liked by the authenticated user
        $likedUserIds = UserAction::where('user_id', $user->id)
                                  ->where('action', 'like')
                                  ->pluck('target_user_id');

        // Get the IDs of users disliked by the authenticated user
        $dislikedUserIds = UserAction::where('user_id', $user->id)
                                     ->where('action', 'dislike')
                                     ->pluck('target_user_id');

        // Get the users who were liked but not disliked
        $filteredUsers = User::whereIn('id', $likedUserIds)
                             ->whereNotIn('id', $dislikedUserIds)
                             ->get();

        // Return the filtered list of users
        return response()->json($filteredUsers);
    }
}
