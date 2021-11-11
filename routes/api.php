<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/* Paypal payment */
Route::get('/paypal-pay', 'Api\AuthController@paymentForm')->name('paypal-pay');
Route::post('/creat-payment', 'Api\AuthController@creatPayment')->name('creat-payment');
Route::get('/execute-payment', 'Api\AuthController@executePayment')->name('execute-payment'); 

Route::get('/cancel-payment', 'Api\AuthController@cancelPayment')->name('cancel-payment'); 


Route::post('/subscription-payment', 'Api\AuthController@subscriptionPayment')->name('subscription-payment'); 
Route::get('/execute-subscription-payment', 'Api\AuthController@executeSubscriptionPayment')->name('execute-subscription-payment'); 

/* End Paypal payment */

Route::post('/register', 'Api\AuthController@register');
Route::post('/login', 'Api\AuthController@login');
Route::post('/socialLogin', 'Api\AuthController@socialLogin');
Route::post('/verifyOtp', 'Api\AuthController@updateOtpStatus');
//Route::get('/gym_category', 'Api\AuthController@gymCaregory');
//Route::post('/gym_list', 'Api\AuthController@gymLists');
Route::post('/change_password', 'Api\AuthController@changePassword');

Route::post('/check_avilable_user', 'Api\AuthController@checkAvilableUser');



Route::post('/subscription_ending', 'Api\AuthController@subscriptionEnding');
	
Route::group(['middleware'=>'auth:api'],function(){
    Route::post('/user-deatils', 'Api\AuthController@userDeatils');
	Route::get('/gym_category', 'Api\AuthController@gymCaregory');
	Route::post('/gym_list', 'Api\AuthController@gymLists');
	Route::post('/gym_details', 'Api\AuthController@gymDetails');
	//Route::get('/trainer_list', 'Api\AuthController@trainerLists');
	Route::post('/trainer_list', 'Api\AuthController@trainerLists');
	Route::post('/trainer_details', 'Api\AuthController@trainerDetails');
	
	Route::post('/update_profile', 'Api\AuthController@userTrainerProfileUpdate');
	Route::post('/trainer_add_achievement', 'Api\AuthController@trainerAddAchievement');
	Route::post('/trainer_update_membership_rate', 'Api\AuthController@trainerMembershipRate');
	Route::post('/trainer_add_video', 'Api\AuthController@trainerVideo');
	Route::post('/trainer_galary_image', 'Api\AuthController@trainerGalaryImages');
	
	Route::post('/remove_trainer_galary_image', 'Api\AuthController@removeTrainerGalaryImages');
	Route::post('/remove_trainer_achievement', 'Api\AuthController@removeTrainerAchievement');
	Route::post('/remove_trainer_video', 'Api\AuthController@removeTrainerVideo');
	Route::post('/coupon_codes', 'Api\AuthController@couponCodes'); 
	Route::get('/accessories_lists', 'Api\AuthController@accessoriesLists');
	Route::post('/accessories_details', 'Api\AuthController@accessoriesDetails');
		
	Route::post('/product_lists', 'Api\AuthController@productLists');
	Route::post('/product_details', 'Api\AuthController@productDetails');
	
	Route::post('/help_request', 'Api\AuthController@helpMessage');
	Route::post('/update_location', 'Api\AuthController@updateLocation');
	
	Route::get('/home_screen_counts', 'Api\AuthController@homeScreenCount');
	
	Route::post('/contact_sync', 'Api\AuthController@addFriends'); 
	Route::post('/send_friend_request', 'Api\AuthController@sendFriendRequest');
	Route::post('/accept_friend_request', 'Api\AuthController@acceptFriendRequest');
	Route::post('/reject_friend_request', 'Api\AuthController@friendRequestReject');
	Route::post('/cancel_friend_request', 'Api\AuthController@cancelFriendRequest');
	
	
	Route::post('/friends_list', 'Api\AuthController@getFriendsLists');
	Route::post('/search_friend', 'Api\AuthController@getFriendInfoByEmail');
	Route::post('/get_friend_info', 'Api\AuthController@getFriendInfoByUserId');
	
	Route::post('/received_friend_request', 'Api\AuthController@receivedFriendRequest');
	
	Route::post('/park_list', 'Api\AuthController@parkList');	
	Route::post('/trainer_add_park', 'Api\AuthController@addParkToTrainerProfile');
	Route::post('/remove_trainer_park', 'Api\AuthController@removeTrainerPark');
	
	Route::post('/order_info_by_transaction_id', 'Api\AuthController@getOrderInfoByTransactionId');
	Route::post('/subscription_info_by_transaction_id', 'Api\AuthController@getSubscriptionInfoByTransactionId'); 
	
	Route::post('/trainer_subscription_info_by_transaction_id', 'Api\AuthController@getSubscriptionInfoByTransactionIdForTrainer'); 
	
	
	Route::post('/my_order', 'Api\AuthController@myOrder');
	Route::post('/my_order_trainer', 'Api\AuthController@myOrderTrainer');
	
	Route::post('/cancel_order', 'Api\AuthController@cancelOrder');
	
	
	
	
	Route::post('/add_shipping_address', 'Api\AuthController@addShippingAddress');
	Route::post('/update_shipping_address', 'Api\AuthController@updateShippingAddress');
	Route::post('/get_shipping_address', 'Api\AuthController@getShippingAddress');
	Route::post('/remove_shipping_address', 'Api\AuthController@removeShippingAddress');
	
	
	Route::post('/add_wall', 'Api\AuthController@addWall');
	
	Route::post('/like', 'Api\AuthController@wallLike');
	Route::post('/dislike', 'Api\AuthController@wallDislike');
	
	
	Route::post('/report_to_admin', 'Api\AuthController@reportToAdmin');
	Route::post('/wall_list', 'Api\AuthController@getWallList');
	
	Route::post('/add_wall_comment', 'Api\AuthController@addWallComment');
	Route::post('/get_wall_comment', 'Api\AuthController@getWallComment');
	
	Route::post('/get_notifications', 'Api\AuthController@getNotifications');
	
	
	Route::post('/add_challange', 'Api\AuthController@addChallange');
	Route::post('/accept_challange', 'Api\AuthController@acceptChallange');
	Route::post('/complete_challange', 'Api\AuthController@completeChallange');
	
	Route::post('/challange_sent_list', 'Api\AuthController@challangeRequestSendList');
	Route::post('/challange_received_list', 'Api\AuthController@challangeRequestReceivedList');
	Route::post('/challange_request_completed_list', 'Api\AuthController@challangeRequestCompletedList');
	
	
	Route::post('/push_test', 'Api\AuthController@push_test');
	
	Route::post('/challange_friends_list', 'Api\AuthController@getChallangeFriendsLists');
	
	Route::post('/add_firebase_id', 'Api\AuthController@addFirbaseId');

 Route::post('/search_trainer', 'Api\AuthController@searchTrainer');
 Route::post('/add_trainer_home_service', 'Api\AuthController@addTrainerHomeService');
 Route::post('/update_trainer_home_service', 'Api\AuthController@updateTrainerHomeService');
 Route::post('/remove_trainer_home_service', 'Api\AuthController@removeTrainerHomeService');
 
 
 Route::post('/trainer_wallet', 'Api\AuthController@trainerWallet');
 Route::post('/request_to_admin', 'Api\AuthController@requestToAdmin');
 
 Route::post('/trainer_wallet_history', 'Api\AuthController@trainerWalletHistory');
 Route::post('/submit_feedback', 'Api\AuthController@feedBack');
 
 Route::post('/update_trainer_account_details', 'Api\AuthController@addTrainerPaypalId');
 
 
 Route::post('/trainer_park_list', 'Api\AuthController@trainerParkList');	
 Route::post('/trainer_park_detail', 'Api\AuthController@trainerParkDetails');	
 Route::post('/park_going_in', 'Api\AuthController@parkGoingIn');	
 Route::post('/share_your_experience', 'Api\AuthController@shareYourExperience');	
 Route::post('/park_gallery', 'Api\AuthController@parkGallery');	
 
 Route::post('/user_complete_profile', 'Api\AuthController@userCompleteProfile');	
 
});
	Route::get('/terms_of_use_user', 'Api\AuthController@termsOfUseForUser');
	Route::get('/privacy_policy', 'Api\AuthController@privacyPolicy');
	Route::get('/about_us', 'Api\AuthController@aboutUs');
	Route::get('/terms_of_use_trainer', 'Api\AuthController@termsOfUseForTrainer');

Route::post('/password/email', 'Api\ForgotPasswordController@sendResetLinkEmail');
Route::post('/password/reset', 'Api\ResetPasswordController@reset');