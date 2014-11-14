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
Route::get('login', 'LoginController@index');
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
Route::get('/dashboard', 'DashboardController@index');
Route::resource('dashboard', 'DashboardController');
/* END OF DASHBOARD RELATED */

/* TOPUP RELATED */
Route::get('/topup', 'TopupController@index');
Route::resource('topup', 'TopupController');
/* END OF TOPUP RELATED */

/* QUESTIONS RELATED */
Route::get('ask/{category}', array('as' => 'ask.question', 'uses' => 'AskController@ask_question'));
Route::get('questions', array('as' => 'ask.list', 'uses' => 'AskController@question_list'));
Route::post('ask/store', array('as' => 'ask.store', 'uses' => 'AskController@store'));
Route::get('answer/{id}', array('as' => 'ask.answer', 'uses' => 'AskController@show_answer'));
Route::resource('ask', 'AskController');
/* END OF QUESTIONS RELATED */

/* EXPERT RELATED */
Route::get('expert/{category}', array('as' => 'expert.show_list', 'uses' => 'ExpertController@show_list'));
Route::resource('expert', 'ExpertController');
/* END OF EXPERT RELATED */
