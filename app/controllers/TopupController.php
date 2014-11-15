<?php

class TopupController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$user = User::where('users.id', Auth::user()->id)->select('credits')->first();
			
		return View::make('topup.index')
		->withUser( $user )
		->withUser_( Auth::user() );
	}

	public function sale()
	{
		$input = Input::except(array("q", "_token"));
		$rules = array(
			'price' => [
                'in' => 'in:1,2,5',
                'required' => "required",
            ],
		);

		$validator = Validator::make($input, $rules);

		if($validator->passes())
		{
			$brain = new BraintreeManager;
			//
			// do transactions
			//
			$user = Auth::user();
			
			$result = $brain->sale($user->id, $input['price']);

			if($result->success)
			{
				//
				// if success
				// first save history
				//
				$data = array(
					'user_id' => $user->id,
					'trx_id' => $result->transaction->id,
					'status' => $result->transaction->status,
					'amount' => $result->transaction->amount,
				);

				$trans = new Transactions;
				$trans->fill($data);
				$trans->save();
				//
				// second, update credit
				//
				$point = array(
					1 => 100,
					2 => 250,
					5 => 700,
				);

				$user->credits += $point[$input['price']];
				$user->save();

				return Redirect::route('topup.index')->with('done', '<strong>Success</strong>: Topup succesfully, your current point now : ' . $user->credits);
			}
			else
			{
				$data = array(
					'user_id' => $user->id,
					'trx_id' => $result->transaction->id,
					'status' => $result->transaction->status,
					'amount' => $result->transaction->amount,
				);

				$trans = new Transactions;
				$trans->fill($data);
				$trans->save();	
				
				$message = $result->message;
				return Redirect::route('topup.xml_get_current_byte_index(parser)')->with('fail', '<strong>Fail</strong>: ' . $message);			
			}
		}

		
	}
}
