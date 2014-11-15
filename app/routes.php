<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

/* MAIN PAGE */
Route::get('/', function()
{
	return View::make('main');
});
/* END OF MAIN PAGE */

/* LOGIN RELATED */
Route::get('login', array('as' => 'login', 'uses' => 'LoginController@index'));
Route::post('login/forgot_password', 'LoginController@doForgotPassword');
Route::get('login/reset_password', array('as'=>'reset_admin_password','uses'=>'LoginController@resetPassword'));
Route::post('login/reset_password', 'LoginController@doResetPassword');
Route::post('login/login_process', 'LoginController@login_process');
Route::post('login/register', array('as'=>'login.register','uses'=>'LoginController@doRegister'));
Route::resource('login','LoginController');

Route::get('/logout', 'LoginController@logout');
/* END OF LOGIN RELATED */

/*
Route::get('/login', function()
{
	return Redirect::to('login');
});
*/

/* DASHBOARD RELATED */
Route::group(array('before' => 'roleUser'), function () {
	Route::get('/dashboard', array('as' => 'dashboard', 'uses' => 'DashboardController@index'));
});

Route::group(array('before' => 'roleExpert'), function () {
	Route::get('/expert/dashboard', array('as' => 'expert.dashboard', 'uses' => 'DashboardController@expertIndex'));
});
/* END OF DASHBOARD RELATED */

/* TOPUP RELATED */
Route::group(array('before' => 'roleUser'), function () {
	Route::get('/topup', 'TopupController@index');
});
/* END OF TOPUP RELATED */

/* QUESTIONS RELATED */
Route::group(array('before' => 'roleUser'), function () {
	Route::get('ask', array('as' => 'ask.index', 'uses' => 'AskController@index'));
	Route::get('ask/{category}', array('as' => 'ask.question', 'uses' => 'AskController@ask_question'));
	Route::get('questions', array('as' => 'ask.list', 'uses' => 'AskController@question_list'));
	Route::post('ask/store', array('as' => 'ask.store', 'uses' => 'AskController@store'));
	Route::get('answer/{id}', array('as' => 'ask.answer', 'uses' => 'AskController@show_answer'));
});

Route::group(array('before' => 'roleExpert'), function () {
	Route::get('reply/{id}', array('as' => 'ask.reply', 'uses' => 'AskController@reply_question'));
	Route::post('ask/doreply', array('as' => 'ask.doreply', 'uses' => 'AskController@doReply'));
	Route::get('answer/{id}', array('as' => 'ask.answer', 'uses' => 'AskController@show_answer'));
});
/* END OF QUESTIONS RELATED */

/* EXPERT RELATED */
Route::group(array('before' => 'roleUser'), function () {
	Route::get('expert', array('as' => 'expert.index', 'uses' => 'ExpertController@index'));
	Route::get('expert/{category}', array('as' => 'expert.show_list', 'uses' => 'ExpertController@show_list'));
});
/* END OF EXPERT RELATED */


/* PROFILE RELATED */
Route::group(array('before' => 'roleUserOrExpert'), function () {
	Route::get('profile', array('as' => 'profile.index', 'uses' => 'ProfileController@index'));
});
Route::group(array('before' => 'roleUser'), function () {
	Route::put('profile/me', array('as' => 'profile.update.me', 'uses' => 'ProfileController@userUpdateMe'));
});
Route::group(array('before' => 'roleExpert'), function () {
	Route::put('expert/profile/me', array('as' => 'expert.profile.update.me', 'uses' => 'ProfileController@expertUpdateMe'));
});
