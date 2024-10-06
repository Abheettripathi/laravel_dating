<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InterestsController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\RelegionController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UpadateController;
use App\Http\Controllers\UserMediaController;
use App\Http\Controllers\OTPController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserActionController;
use App\Http\Controllers\PromoCodeController;
use App\Http\Controllers\PromptController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PromoCodeValidityController;
use App\Http\Controllers\LongController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\Regional_GroupController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\QuizAnswerController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\EventController;


// Authentication routes

// Register a new user
Route::post('register', [RegisterController::class, 'register']);

// Login user
Route::post('login', [LoginController::class, 'login'])->name('login');

// Logout user
Route::post('logout', [LoginController::class, 'logout']);

// Authenticated routes group
Route::middleware('auth:api')->group(function () {

    // User Action Routes
    
    // Store user action (like, dislike, etc.)
    Route::post('user-action',[UserActionController::class , 'store']);
    
    // Get list of users who liked the authenticated user
    Route::get('user-actions/who-liked-me',[UserActionController::class, 'getWhoLikedMe']);
    
    // Get mutual likes between users
    Route::get('user-action/mutual-likes', [UserActionController::class,'getMutualLikes']);
    
    // Get all likes and dislikes
    Route::get('user-actions/all', [UserActionController::class, 'getAllLikesAndDislikes']);
    
    // Convert a like to a dislike
    Route::post('/user/convert-like-to-dislike', [UserActionController::class, 'convertLikeToDislike']);
    
    // Remove a mutual like
    Route::post('/user/remove-mutual-like', [UserActionController::class, 'removeMutualLike']);

    // Test route to check if API is working
    Route::post('/test-route', function () {
        return 'Route is working';
    });

    // Regional Group CRUD operations
    Route::apiResource('Regional_Group', Regional_GroupController::class);

    // Quiz Routes
    
    // List all quizzes
    Route::get('quizzes', [QuizController::class, 'index']);
    
    // Create a new quiz
    Route::post('quizzes', [QuizController::class, 'store']);
    
    // Show details of a specific quiz
    Route::get('quizzes/{id}', [QuizController::class, 'show']);
    
    // Update a quiz
    Route::put('quizzes/{id}', [QuizController::class, 'update']);
    
    // Delete a quiz
    Route::delete('quizzes/{id}', [QuizController::class, 'destroy']);
    
    // Get a random quiz
    Route::get('random1',[QuizController::class, 'random']);
    
    // Email sending route
    Route::get('email_send',[UserController::class,'email_send']);
    
    // User interest routes
    
    // Get interests of a specific user
    Route::get('/user-interest/{id}', [UserController::class, 'getUserInterest']);
    
    // Get users based on a specific interest
    Route::get('/interest/{id}/users', [UserController::class, 'getUsersByInterest']);
    
    // Filter users based on country, city, interest, religion, etc.
    Route::post('filter-users', [UserController::class, 'filterUsers']);
    
    // OTP routes
    
    // Send OTP
    Route::post('send-otp', [OTPController::class, 'sendOTP']);
    
    // Verify OTP
    Route::post('/verify-otp', [OTPController::class, 'verifyOTP']);
    
    // File upload route
    Route::post('user-media/upload', [UserMediaController::class, 'store']);
    
    // User profile update or store
    Route::post('/profiletest', [UpadateController::class, 'storeOrUpdate']);

    // Filter users by age (using date of birth)
    Route::get('/filter-usersdob', [UserController::class, 'filterByAgeGroups']);
    Route::get('date-of-birth',[UserController::class,'filterByAgeGroups1']);
    Route::get('date-of-birth1',[UserController::class,'filterByAgeGroups2']);
    Route::get('users-by-ages', [UserController::class, 'getUsersByAges']);

    // Country CRUD routes
    Route::get('countries', [CountryController::class, 'index']);
    Route::post('countries', [CountryController::class, 'store']);
    Route::get('countries/{id}', [CountryController::class, 'show']);
    Route::put('countries/{id}', [CountryController::class, 'update']);
    Route::delete('countries/{id}', [CountryController::class, 'destroy']);

    // Religion CRUD routes (same as country)
    Route::get('religions', [CountryController::class, 'index']);
    Route::post('religions', [CountryController::class, 'store']);
    Route::get('religions/{id}', [CountryController::class, 'show']);
    Route::put('religions/{id}', [CountryController::class, 'update']);
    Route::delete('religions/{id}', [CountryController::class, 'destroy']);

    // States CRUD routes
    Route::get('states', [CountryController::class, 'index']);
    Route::post('states', [CountryController::class, 'store']);
    Route::get('states/{id}', [CountryController::class, 'show']);
    Route::put('states/{id}', [CountryController::class, 'update']);
    Route::delete('states/{id}', [CountryController::class, 'destroy']);

    // City CRUD routes
    Route::get('cities', [CountryController::class, 'index']);
    Route::post('cities', [CountryController::class, 'store']);
    Route::get('cities/{id}', [CountryController::class, 'show']);
    Route::put('cities/{id}', [CountryController::class, 'update']);
    Route::delete('cities/{id}', [CountryController::class, 'destroy']);

    // Promo Code Routes
    Route::post('/promo-codes', [PromoCodeValidityController::class, 'createPromoCode']);
    Route::get('/promo-codes/{code}', [PromoCodeValidityController::class, 'checkPromoCodeValidity']);
    Route::get('promo-codes1/{code}',[PromoCodeValidityController::class,'checkPromoCodeValidity1']);

    // Users nearby route (location-based)
    Route::get('/users/nearby', [LongController::class, 'nearby']);

    // Block and unblock users
    Route::post('block-user', [BlockController::class, 'blockUser']);
    Route::post('unblock-user', [BlockController::class, 'unblockUser']);
    Route::get('check-block-status',[BlockController::class , 'checkuser']);

    // Get user count routes
    Route::get('/users/count', [UserController::class, 'getUserCount']);
    Route::get('/users/count1', [UserController::class, 'getUserCount1']);

    // Filtered users (e.g. based on certain actions)
    Route::get('/filtered-users', [UserActionController::class, 'getFilteredUsers']);

    // Answer CRUD routes and winner determination by question
    Route::apiResource('answers', AnswerController::class);
    Route::get('/answer/determine-winner/{questionId}', [AnswerController::class, 'determineWinnerByQuestion']);

    // Room creation route
    Route::post('/room', [RoomController::class, 'store']);

    // Quiz answer route
    Route::post('/quiz-answers', [QuizAnswerController::class, 'store']);

    // Get top quiz scorer
    Route::get('/top-scorer', [QuizController::class, 'getTopScorer']);

    // Get user's correct quiz answers
    Route::get('/user/{userId}/correct-answers', [QuizController::class, 'getUserCorrectAnswers']);
    Route::get('/user/{userId}/correct-answers-count', [QuizController::class, 'getUserCorrectAnswersCount']);

    // Promo code CRUD operations
    Route::get('promocode',[PromoCodeController::class,'index']);
    Route::post('promocode', [PromoCodeController::class, 'store']);
    Route::put('promocode/{id}',[PromoCodeController::class,'update']);
    Route::get('promocode/{id}',[PromoCodeController::class , 'show']);
    Route::delete('promocode/{id}',[PromoCodeController::class ,'destroy']);

    // Prompt CRUD routes
    Route::get('prompt',[PromptController::class ,'index']);
    Route::post('prompt',[PromptController::class, 'store']);
    Route::get('prompt/{id}',[PromptController::class, 'show']);
    Route::put('prompt/{id}',[PromptController::class ,'update']);
    Route::delete('prompt/{id}',[PromptController::class ,'destroy']);

    // Password reset routes
    Route::post('requestReset',[PasswordResetController::class, 'requestReset']);
    Route::post('resetPassword',[PasswordResetController::class ,'resetPassword']);


    Route::get('packages', [PackageController::class, 'index']);
    Route::post('packages', [PackageController::class, 'store']);
    Route::get('packages/{id}', [PackageController::class, 'show']);
    Route::put('packages/{id}', [PackageController::class, 'update']);
    Route::delete('packages/{id}', [PackageController::class, 'destroy']);
   
    Route::get('package/price',[PackageController::class , 'filterByPrice']);
    Route::get('package/search', [PackageController::class, 'searchByName']);
    Route::get('package/expensive',[PackageController::class , 'mostExpensivePackage']);
    Route::get('package/discount',[PackageController::class , 'packagesWithDiscount']);
    Route::get('package/paginated',[PackageController::class , 'indexPaginated']);
    Route::get('package/latest',[PackageController::class,'latestPackages']);
    Route::delete('package/deleted',[PackageController::class , 'bulkDelete']);

   Route::post('send-notification', [NotificationController::class, 'sendNotification']);
   
   Route::get('event',[EventController::class , 'index']);
   Route::post('event',[EventController::class , 'store']);
   Route::get('event/{id}',[EventController::class , 'show']);
   Route::put('event/{id}',[EventController::Class ,'update']);
   Route::delete('event/{id}',[EventController::class , 'delete']);
   Route::get('/events', [EventController::class, 'index1']);
   
});

// Get users excluding those who are blocked
Route::get('/users-excluding-blocked', [UserController::class, 'getUsersExcludingBlocked']);

