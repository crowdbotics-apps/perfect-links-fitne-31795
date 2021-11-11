<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return view('welcome');
});

// Route::get('/login', function () {
//     return view('login');
// });


Route::get('/paypal-pay', 'PayPalPaymentController@paymentForm')->name('paypal-pay');

Route::get('/cancel-payment', function () {
    return view('paypal/success', ['txn_id'=>'TRASACTIONDECLINED']);
});
//Route::get('/paypal-subscription-pay', 'PayPalPaymentController@paymentSubscriptionForm')->name('paypal-subscription-pay');

//Route::post('/creat-payment', 'PayPalPaymentController@creatPayment')->name('creat-payment');
//Route::get('/execute-payment', 'PayPalPaymentController@executePayment')->name('execute-payment');



/* Route::get('/paypal', 'PayPalController@paypal')->name('paypal');
Route::get('/payment', 'PayPalController@payment')->name('payment');
Route::get('/cancel', 'PayPalController@cancel')->name('payment.cancel');
Route::get('/payment/success', 'PayPalController@success')->name('payment.success'); */


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');



/* Admin */

Route::get('/home', 'Auth\LoginController@logout')->name('home');
Route::get('/admin/dashboard', 'LoginController@index')->name('admin_login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');

/* Page Content */
Route::get('/admin/add_page', 'AdminController@pageContent')->name('add_page');
Route::post('/admin/add_page', 'AdminController@pageContent')->name('add_page');
Route::get('/admin/update_page/{id?}', 'AdminController@updateContent')->name('update_page');
Route::post('/admin/update_page', 'AdminController@updateContent')->name('update_page');

/* Gym category */
Route::get('/admin/add_gym_category', 'AdminController@addGymCategory')->name('add_gym_category');
Route::post('/admin/add_gym_category', 'AdminController@addGymCategory')->name('add_gym_category');
Route::get('/admin/category_list', 'AdminController@gymCategoryLists')->name('category_list');
Route::get('/admin/edit_gym_category/{id?}', 'AdminController@editGymCategory')->name('gym_category_edit');
Route::post('/admin/edit_gym_category', 'AdminController@editGymCategory')->name('gym_category_edit');

/* Add & List & Edit Park */
Route::get('/admin/add_park', 'AdminController@addPark')->name('add_park');
Route::post('/admin/add_park', 'AdminController@addPark')->name('add_park');
Route::get('/admin/park_list', 'AdminController@parkLists')->name('park_list');
Route::get('/admin/edit_park/{id?}', 'AdminController@editPark')->name('edit_park');
Route::post('/admin/edit_park', 'AdminController@editPark')->name('edit_park');



/* Gym Equipments */
Route::get('/admin/add_gym_equipments', 'AdminController@addGymEquipments')->name('add_gym_equipments');
Route::post('/admin/add_gym_equipments', 'AdminController@addGymEquipments')->name('add_gym_equipments');
Route::get('/admin/equipment_list', 'AdminController@euquipmentLists')->name('equipment_list');
Route::get('/admin/edit_equipment/{id?}', 'AdminController@editEquipment')->name('edit_equipment');
Route::post('/admin/edit_equipment', 'AdminController@editEquipment')->name('edit_equipment');

/* Add Gym */
Route::get('/admin/add_gym', 'AdminController@addGym')->name('add_gym');
Route::post('/admin/add_gym', 'AdminController@addGym')->name('add_gym');
Route::post('/admin/add_gym_euqupment_quantity', 'AdminController@addGymEuqupmentQuantity')->name('add_gym_euqupment_quantity');
Route::post('/admin/update_gym_euqupment_quantity', 'AdminController@updateGymEuqupmentQuantity')->name('update_gym_euqupment_quantity');
Route::post('/admin/add_gym_subscription_details', 'AdminController@addGymSubscriptionDetails')->name('add_gym_subscription_details');
Route::post('/admin/update_gym_subscription_details', 'AdminController@updateGymSubscriptionDetails')->name('update_gym_subscription_details');
Route::get('/admin/gym_list', 'AdminController@gymList')->name('gym_list');

Route::get('/admin/edit_gym_profile/{id?}', 'AdminController@editGymProfile')->name('edit_gym_profile');
Route::post('/admin/edit_gym_profile', 'AdminController@editGymProfile')->name('edit_gym_profile');
//Route::post('/admin/update_gym_profile', 'AdminController@updateGymProfile')->name('update_gym_profile');

/* Add Coupon */
Route::get('/admin/add_coupon', 'AdminController@addCoupon')->name('add_coupon');
Route::post('/admin/add_coupon', 'AdminController@addCoupon')->name('add_coupon');
Route::get('/admin/coupon_list', 'AdminController@couponList')->name('coupon_list');
Route::post('/admin/remove_coupon', 'AdminController@removeCoupon')->name('remove_coupon');

/* Add Product */
Route::get('/admin/add_product', 'AdminController@addProduct')->name('add_product');
Route::post('/admin/add_product', 'AdminController@addProduct')->name('add_product');
Route::get('/admin/product_list', 'AdminController@productList')->name('product_list');
Route::get('/admin/edit_product/{id?}', 'AdminController@editProduct')->name('edit_product');
Route::post('/admin/edit_product', 'AdminController@editProduct')->name('edit_product');
Route::post('/admin/remove_product_accessorie_image', 'AdminController@removeProductAccessorieImage')->name('remove_product_accessorie_image.post');

/* Add Accessories */
Route::get('/admin/add_accessorie', 'AdminController@addAccessories')->name('add_accessorie');
Route::post('/admin/add_accessorie', 'AdminController@addAccessories')->name('add_accessorie');
Route::get('/admin/accessories_list', 'AdminController@accessoriesList')->name('accessories_list');
Route::get('/admin/edit_accessorie/{id?}', 'AdminController@editAccessorie')->name('edit_accessorie');
Route::post('/admin/edit_accessorie', 'AdminController@editAccessorie')->name('edit_accessorie');


/* Sale Reports */

Route::get('/admin/product_sold', 'AdminController@productSold')->name('product_sold');
Route::post('/admin/add_tracking_details', 'AdminController@addTrackingDetails')->name('add_tracking_details');

Route::post('/admin/add_user_gym_id', 'AdminController@addUserGymId')->name('add_user_gym_id');
//Route::get('/admin/get_tracking_details', 'AdminController@getTrackingDetails')->name('get_tracking_details');
Route::get('/admin/accessories_sold', 'AdminController@accessoriesSold')->name('accessories_sold');


Route::get('/admin/gym_subscritpion_list', 'AdminController@gymSubscritpionList')->name('gym_subscritpion_list');
Route::get('/admin/trainer_subscritpion_list', 'AdminController@trainerSubscritpionList')->name('trainer_subscritpion_list');

Route::get('/admin/update_membership_rate', 'AdminController@updateMembershipRate')->name('update_membership_rate');
Route::post('/admin/update_membership_rate', 'AdminController@updateMembershipRate')->name('update_membership_rate');

Route::get('/admin/trainer_wallet_request', 'AdminController@trainerWalletRequest')->name('trainer_wallet_request');

Route::get('/admin/wallet_subscription_details/{trainerId?}/{dateTime?}', 'AdminController@subscriptionDetailsByWalletRequest')->name('wallet_subscription_details');

Route::post('/admin/add_trainer_payment_details', 'AdminController@addTrainerPaymentDetails')->name('add_trainer_payment_details');





