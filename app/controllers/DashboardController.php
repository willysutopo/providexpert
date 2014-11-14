<?php

class DashboardController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// if user has not logged in
		if ( !Auth::check() )
		{			
			return Redirect::to("/login");
		}

		$user = DB::table('users')
			->select(DB::raw('count(questions.id) as question_count, count(answers.id) as answer_count, users.id, users.fullname, users.credits'))
			->leftJoin('questions', 'questions.asker_id', '=', 'users.id')
			->leftJoin('answers', 'answers.question_id', '=', 'questions.id')
			->where('users.id', Auth::user()->id)
			->groupBy('users.id')
			->groupBy('users.fullname')
			->groupBy('users.credits')
			->first();
		
		return View::make('dashboard.index')->withUser( $user );
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


}
