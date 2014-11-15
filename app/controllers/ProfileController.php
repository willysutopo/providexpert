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
		if ( !Auth::check() )
		{
			return View::make('login.show');
		}
		else
		{
			$user = Auth::user();
			if (Entrust::hasRole('Expert')) {
				return View::make('profile.expert')->withUser( $user );
			} else {
				return View::make('profile.user')->withUser( $user );
			}
		}		
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
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
