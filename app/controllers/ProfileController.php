<?php

class ProfileController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	
	public function __construct()
	{
		$this->beforeFilter('csrf', array('on' => ['post','put']));
	}

	public function index()
	{
		
		if (Entrust::hasRole('Expert')) {
			$user = Auth::user()->with('expert')->first();

			// dpd($user->toArray());

			$categories = Category::all();

			return View::make('profile.expert')
				->with('user', $user)
				->with('categories', $categories)
			;
		} else {
			$user = Auth::user();
			return View::make('profile.user')
				->with('user', $user)
			;
		}		
	}

	public function userUpdateMe()
	{
		$input = Input::except('q', '_method', '_token');

		$validator = Validator::make($input, array(
			'fullname' => 'required',
			'email' => 'required|email',
			'address' => 'required',
			'city' => 'required',
			'phone' => 'required',
		));
		$validator->setAttributeNames(array(
			'fullname' => 'name'
		));

		if ($validator->fails()) {
			return Redirect::back()
				->withErrors($validator)
				->withInput()
			;
		} else {
			$user = Auth::user();
			$user->fill($input);
			$user->save();
			return Redirect::route('profile.index')
				->withMessage('Profile updated')
			;
		}
	}

	public function expertUpdateMe()
	{

	}

	// do register new account
	public function doSave()
	{
		$rules = array(
	      'email' => 'unique:users,email',
	    );

		$messages = array(
	    'unique' => 'Email already exists in database.',
		);

		// pass input to validator
		$validator = Validator::make( Input::all(), $rules, $messages );
			
		if ( $validator->fails() )
		{
			return Redirect::to('login')->withErrors($validator)->withInput();	
		}

		$fullname = ucwords(Input::get('fullname'));
		$email = strtolower(Input::get('email'));
		$address = Input::get('address');
		$city = Input::get('city');
		$country = Input::get('country');
		$phone = Input::get('phone');
		$password = Input::get('password');

		$user = new User();
		$user->fullname = $fullname;
		$user->email = $email;
		$user->password = Hash::make($password);
		$user->address = $address;
		$user->city = $city;
		$user->country = $country;
		$user->phone = $phone;	
		$user->status = 'A';		
		$user->photo = '';
		$user->timezone = '';
		$user->credits = 50; // 50 free initial credits
		$user->last_login = date("Y-m-d H:i:s");		
		$user->save();

		//
		// save user to braintree
		// 
		$brain = new BraintreeManager;
		$name = explode(" ", $fullname);
		$brain->createUser($user->id, $name[0], (isset($name[1]))?$name[1]:"");

		// Set new registered user role as "User"
		$role = Role::where('name', '=', 'User')->first();
		$user->attachRole($role);

		return Redirect::to('login')->withMessage('You have registered successfully. Please login using your email and password');
	}

}
