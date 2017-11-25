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

/*Route::get('/', function () {
    return view('auth.login');
});*/

Route::post('register/company_verify_store/{id}', 'Auth\RegisterController@companyVerifyStore');
Auth::routes();
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
Route::get('register/verify/{token}', 'Auth\RegisterController@verify'); 
Route::get('register/company_verify/{token}', 'Auth\RegisterController@companyVerify'); 

Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');

/*SOCIALITE AUTHENTICATION ROUTE SECTION*/
Route::get('auth/{provider}', 'Auth\LoginController@redirectToProvider');
Route::get('auth/{provider}/callback', 'Auth\LoginController@handleProviderCallback');


/*PINTEREST AUTHENTICATION ROUTE SECTION*/
Route::get('custom-auth/{provider}', 'Auth\LoginController@redirectToCustomProvider');
Route::get('custom-auth/{provider}/callback', 'Auth\LoginController@handleCustomProviderCallback');

/*PAYMENT*/
Route::get('payment','Auth\LoginController@getPayment');
Route::post('payment','Auth\LoginController@postPayment');

Route::get('manageMailChimp', 'MailChimpController@manageMailChimp');
Route::post('subscribe',['as'=>'subscribe','uses'=>'MailChimpController@subscribe']);
Route::post('sendCompaign',['as'=>'sendCompaign','uses'=>'MailChimpController@sendCompaign']);

/*USER MANAGEMENT*/
Route::group(['middleware' => ['role:admin|user|company']], function()
{
	/*PROFILE*/
	Route::get('user/getDatas','UserController@getDatas');
	Route::get('user/profile','UserController@profile');
	Route::post('user/profile','UserController@postProfile');

	Route::resource('user','UserController');
	

    Route::get('api_log','ApiLogController@index');
	Route::get('logs','SettingController@logs');
	Route::post('change-password','UserController@resetPassword');
});

/*ADMIN ROUTE*/
Route::group(['middleware' => ['role:admin']], function(){
	Route::get('customer/{id}/convert-to-user','CustomerController@convertToUser');
	Route::post('customer/create-secret-key','CustomerController@createSecretKey');
	Route::get('customer/getDatas','CustomerController@getDatas');
	Route::resource('customer','CustomerController');
	
	Route::resource('role','RoleController');
	Route::resource('permission','PermissionController');
	Route::get('role/{id}/permission', 'RoleController@permissions');
	Route::post('role/{id}/permission', 'RoleController@permissionsStore');

	Route::resource('setting','SettingController');
    Route::post('add-question','SettingController@addQuestion');
    Route::get('logs/view','ApiLogController@view');
}); 