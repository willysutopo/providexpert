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

			$categories = Category::all(array('id', 'category_name'));
			$categoryList = [];
			foreach ($categories as $category) {
				$categoryList[$category->id] = $category->category_name;
			}


			return View::make('profile.expert')
				->with('user', $user)
				->with('categoryList', $categoryList)
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
		$input = Input::except('q', '_method', '_token');

		$validator = Validator::make($input, array(
			'expert_name' => 'required',
			'email' => 'required|email',
			'category_id' => 'required',
			'expertises' => 'required',
			'address' => 'required',
			'phone' => 'required',
		));
		$validator->setAttributeNames(array(
			'expert_name' => 'name',
			'category_id' => 'category',
		));

		if ($validator->fails()) {
			return Redirect::back()
				->withErrors($validator)
				->withInput()
			;
		} else {
			$user = Auth::user()->with('expert')->first();
			$user->fill(array(
				'email' => $input['email'],
				'address' => $input['address'],
				'phone' => $input['phone'],
			));

			$user->expert->fill(array(
				'expert_name' => $input['expert_name'],
				'category_id' => $input['category_id'],
				'expertises' => $input['expertises'],
			));

			$user->push();

			return Redirect::route('profile.index')
				->withMessage('Profile updated')
			;
		}
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

}


