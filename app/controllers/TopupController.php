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
		
		return View::make('topup.index')->withUser( $user );
	}
}
