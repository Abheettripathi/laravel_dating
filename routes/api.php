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

Route::post('register', [RegisterController::class, 'register']);


Route::post('login', [LoginController::class, 'login'])->name('login');


Route::post('logout', [LoginController::class, 'logout']);


Route::middleware('auth:api')->group(function () {

    
    Route::post('user-action',[UserActionController::class , 'store']);
    
    
    Route::get('user-actions/who-liked-me',[UserActionController::class, 'getWhoLikedMe']);
    
    
    Route::get('user-action/mutual-likes', [UserActionController::class,'getMutualLikes']);
    
    
    Route::get('user-actions/all', [UserActionController::class, 'getAllLikesAndDislikes']);
    
    
    Route::post('/user/convert-like-to-dislike', [UserActionController::class, 'convertLikeToDislike']);
    
    
    Route::post('/user/remove-mutual-like', [UserActionController::class, 'removeMutualLike']);

    
    Route::apiResource('Regional_Group', Regional_GroupController::class);

    
    Route::get('quizzes', [QuizController::class, 'index']);
    
    
    Route::post('quizzes', [QuizController::class, 'store']);
    
    
    Route::get('quizzes/{id}', [QuizController::class, 'show']);
    
    
    Route::put('quizzes/{id}', [QuizController::class, 'update']);
    
    
    Route::delete('quizzes/{id}', [QuizController::class, 'destroy']);
    
    
    Route::get('random1',[QuizController::class, 'random']);
    
    
    Route::get('email_send',[UserController::class,'email_send']);
    
    
    Route::get('/user-interest/{id}', [UserController::class, 'getUserInterest']);
    

    Route::get('/interest/{id}/users', [UserController::class, 'getUsersByInterest']);
    
    
    Route::post('filter-users', [UserController::class, 'filterUsers']);
    
    
    Route::post('send-otp', [OTPController::class, 'sendOTP']);
    
    
    Route::post('/verify-otp', [OTPController::class, 'verifyOTP']);
    
    
    Route::post('user-media/upload', [UserMediaController::class, 'store']);
    

    Route::post('/profiletest', [UpadateController::class, 'storeOrUpdate']);
    Route::get('/filter-usersdob', [UserController::class, 'filterByAgeGroups']);
    Route::get('date-of-birth',[UserController::class,'filterByAgeGroups1']);
    Route::get('date-of-birth1',[UserController::class,'filterByAgeGroups2']);
    Route::get('users-by-ages', [UserController::class, 'getUsersByAges']);

    
    Route::get('countries', [CountryController::class, 'index']);
    Route::post('countries', [CountryController::class, 'store']);
    Route::get('countries/{id}', [CountryController::class, 'show']);
    Route::put('countries/{id}', [CountryController::class, 'update']);
    Route::delete('countries/{id}', [CountryController::class, 'destroy']);

    
    Route::get('religions', [RelegionController::class, 'index']);
    Route::post('religions', [RelegionController::class, 'store']);
    Route::get('religions/{id}', [RelegionController::class, 'show']);
    Route::put('religions/{id}', [RelegionController::class, 'update']);
    Route::delete('religions/{id}', [RelegionController::class, 'destroy']);

    
    Route::get('states', [StateController::class, 'index']);
    Route::post('states', [StateController::class, 'store']);
    Route::get('states/{id}', [StateController::class, 'show']);
    Route::put('states/{id}', [StateController::class, 'update']);
    Route::delete('states/{id}', [StateController::class, 'destroy']);

    
    Route::get('cities', [CityController::class, 'index']);
    Route::post('cities', [CityController::class, 'store']);
    Route::get('cities/{id}', [CityController::class, 'show']);
    Route::put('cities/{id}', [CityController::class, 'update']);
    Route::delete('cities/{id}', [CityController::class, 'destroy']);


    Route::post('/promo-codes', [PromoCodeValidityController::class, 'createPromoCode']);
    Route::get('/promo-codes/{code}', [PromoCodeValidityController::class, 'checkPromoCodeValidity']);
    Route::get('promo-codes1/{code}',[PromoCodeValidityController::class,'checkPromoCodeValidity1']);

    
    Route::get('/users/nearby', [LongController::class, 'nearby']);

    Route::post('block-user', [BlockController::class, 'blockUser']);
    Route::post('unblock-user', [BlockController::class, 'unblockUser']);
    Route::get('check-block-status',[BlockController::class , 'checkuser']);

    
    Route::get('/users/count', [UserController::class, 'getUserCount']);
    Route::get('/users/count1', [UserController::class, 'getUserCount1']);

    
    Route::get('/filtered-users', [UserActionController::class, 'getFilteredUsers']);

    
    Route::apiResource('answers', AnswerController::class);
    Route::get('/answer/determine-winner/{questionId}', [AnswerController::class, 'determineWinnerByQuestion']);

    
    Route::post('/room', [RoomController::class, 'store']);


    Route::post('/quiz-answers', [QuizAnswerController::class, 'store']);

    
    Route::get('/top-scorer', [QuizController::class, 'getTopScorer']);

    
    Route::get('/user/{userId}/correct-answers', [QuizController::class, 'getUserCorrectAnswers']);
    Route::get('/user/{userId}/correct-answers-count', [QuizController::class, 'getUserCorrectAnswersCount']);

    
    Route::get('promocode',[PromoCodeController::class,'index']);
    Route::post('promocode', [PromoCodeController::class, 'store']);
    Route::put('promocode/{id}',[PromoCodeController::class,'update']);
    Route::get('promocode/{id}',[PromoCodeController::class , 'show']);
    Route::delete('promocode/{id}',[PromoCodeController::class ,'destroy']);

    
    Route::get('prompt',[PromptController::class ,'index']);
    Route::post('prompt',[PromptController::class, 'store']);
    Route::get('prompt/{id}',[PromptController::class, 'show']);
    Route::put('prompt/{id}',[PromptController::class ,'update']);
    Route::delete('prompt/{id}',[PromptController::class ,'destroy']);

    
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

Route::get('/users-excluding-blocked', [UserController::class, 'getUsersExcludingBlocked']);

