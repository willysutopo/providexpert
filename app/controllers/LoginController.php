<?php

class LoginController extends \BaseController {

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
			return Redirect::to('dashboard');
		}		
	}

	public function login_process()
	{
		//define rules
		$rules = array(
			"email" => array('required'),
			"password" => array('required'),
		);
		
		//pass input to the validator
		$validator = Validator::make(Input::all(), $rules);
		
		//check if not valid
		if ( $validator->fails() )
		{
			return Redirect::to('login')->withErrors($validator)->withInput();	
		}

		$email = Input::get('email');
		$password = Input::get('password');

		// Checking the hashed password, if matched proceed to dashboard, else redirect to login		
		if (Auth::attempt( array('email' => $email, 'password' => $password)) )
		{
			return Redirect::to('dashboard');
		}
		else
		{			
			return Redirect::to('login')->withMessage("Please enter correct email &amp; password")->withEmail(Input::get('email'));				
		}
	}

	public function doForgotPassword()
	{
		$given_email = Input::get('email');
		$admin = Admins::select(array('id','first_name','last_name','email'))->where('email','LIKE',$given_email)->get();
		
		

		/*($transport = Mail::getSwiftMailer()->getTransport();
		$transport->setHost(Config::get('confide_mail.host'));
		$transport->setUsername(Config::get('confide_mail.username'));
		$transport->setPassword(Config::get('confide_mail.password'));*/

		if(isset($admin) && count($admin)!="")
		{
			$admin = $admin[0];
			$first_name = $admin->first_name;
			$last_name = $admin->last_name;
			$email = $admin->email;
			$hashed_email = Hash::make($given_email);
			$token = "i=".$hashed_email."&d=".$admin->id;
			$link =  url("/login/reset_password?$token");
			$to_name = "$first_name $last_name";
			$to_email = "$email";
			$from_name = "Kejora Reset Password Service";
			$from_email = "hello@kejorahq.com";
			$subject = "Reset Password";
			$data = array("first_name"=>$first_name, "last_name"=> $last_name, "token" => $token, "link"=> $link );

			Mail::send('emails.forgot_password', $data, function($message) use ($to_name, $to_email, $from_name, $from_email, $subject )
			{					
				$message->sender($from_email, $from_name);
				$message->from($from_email, $from_name);
				$message->returnPath($from_email);
				$message->replyTo($from_email, $from_name);
				$message->to($to_email, $to_name);
				$message->subject($subject);
			});

			$notice_msg = "Please check your email to reset Kejora Admin Password.";
            return Redirect::action('LoginController@index')
                ->with('notice', $notice_msg);
		}else{
			$error_msg = "Email does not exists";
            return Redirect::action('LoginController@index')
                ->withInput()
                ->with('error', $error_msg);
		}
	}

	public function logout()
	{
		Auth::logout();
		return Redirect::to('/login');
	}

	public function resetPassword()
	{
		$email_hashed = Input::get('i');
		$id = Input::get('d');

		if($id=="" || $email_hashed==""){
			return Redirect::route('login.index')->withMessage("Please click the link which has been sent to your email");
		}else{
			$admin = Admins::where("id",$id)->get();
			if(isset($admin) && count($admin)>0)
			{
				$admin = $admin[0];
				$email = $admin->email;	
				return View::make('login.reset',array('id'=>$id));
			}else{
				return Redirect::route('login.index')->withMessage("Please click the link which has been sent to your email");	
			}
			
		}
		
	}

	public function doresetPassword()
	{
		$id = Input::get("id");
		$password = Input::get("password");
		$admins = Admins::findOrFail($id);
		$admins->password = Hash::make(Input::get('password'));
		$admins->update();
		return Redirect::route('login.index')->withNotice("Your new password has been saved.<br>Please login using your new password.")->withFrom("reset");
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
	public function doRegister()
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

		// Set new registered user role as "User"
		$role = Role::where('name', '=', 'User')->first();
		$user->attachRole($role);

		return Redirect::to('login')->withMessage('You have registered successfully. Please login using your email and password');
	}

}
