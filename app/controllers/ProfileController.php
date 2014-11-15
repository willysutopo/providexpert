<?php

class ProfileController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function index()
	{
		{
			$user = Auth::user();
			if (Entrust::hasRole('Expert')) {
				return View::make('profile.expert')->withUser( $user );
			} else {
				return View::make('profile.user')->withUser( $user );
			}
		}		
	}

	public function paypal()
	{
		{
			$user = Auth::user();
			if (! Entrust::hasRole('Expert')) {
				return View::make('profile.paypal')->withUser( $user );
			}
		}		
	}	

	public function paypalSync()
	{
		$input = Input::except("q");

		$rules = array(
			'cc_num' => "required",
			'cvv_num' => "required",
			'month' => [
                'dataType' => 'numeric',
                'min' => 'min:1',
                'max' => 'max:12',
                'required' => "required",
            ],
			'year' => [
                'dataType' => 'numeric',
                'min' => 'min:' . date("Y", time()),
                'max' => 'max:' . (intval(date("Y", time())) + 10),
                'required' => "required",
            ],
		);
		$validator = Validator::make($input, $rules);

		$user = Auth::user();
		if($validator->passes())
		{
			$user_paypal = $user->paypal;

			//
			// save CC to braintree
			// 
			$brain = new BraintreeManager;
			if($user_paypal)
			{
				//
				// if exist, updat
				//
				$result = $brain->updateCC($user->id, $input['cc_num'], $input['cvv_num'], $input['month'] . "/" . $input['year']);
			}
			else
			{
				//
				// if no exist create new
				//
				$result = $brain->createCC($user->id, $input['cc_num'], $input['cvv_num'], $input['month'] . "/" . $input['year']);
			}

			//
			// result reader
			//
			if(! $result->success)
			{
				$message = $result->message;
				return Redirect::route('profile.paypal')->with('fail', '<strong>Fail</strong>: ' . $message);
			}
			else
			{
				//
				// save to paypal info
				//
				
				$credit = $result->creditCard;
				$paypal = new Paypal;
				$data = array(
					'user_id' => $user->id,
					'token' => $credit->token,
					'expired' => $credit->expirationDate,
					'type' => $credit->cardType,
					'masked' => $credit->maskedNumber,
				);
				//
				// skip validation
				//
				$paypal->fill($data);
				$paypal->save($data);

				return Redirect::route('profile.paypal')->with('done', '<strong>Success</strong>: Your Credit Card Information Sync with Paypal');
			}
		}
		else
		{
			//print_r($validator->messages());
			return Redirect::route('profile.paypal')
			->withErrors($validator->errors());
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
