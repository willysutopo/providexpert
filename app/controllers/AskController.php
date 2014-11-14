<?php

class AskController extends \BaseController {

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
		return View::make('ask.index');
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

	// function to ask questions according to category
	public function ask_question( $category )
	{
		// if user has not logged in
		if ( !Auth::check() )
		{			
			return Redirect::to("/login");
		}

		$category_image = $category . ".jpg";
		$arr_categories = array(
			"health" => "Health",
			"property" => "Property",
			"food" => "Food",
			"love" => "Love",
			"education" => "Education"
		);
		$arr_experts = array(
			"0" => "All Experts",
			"1" => "Dr. Boyke", 
			"2" => "Dr. Bondan", 
			"3" => "Dr. Ronny"
		);

		return View::make('ask.question')->withCategory($category)->withImage($category_image)->withExperts($arr_experts)->withCategories($arr_categories);
	}

	// function to show questions list of currently logged-in user
	public function question_list()
	{
		// if user has not logged in
		if ( !Auth::check() )
		{			
			return Redirect::to("/login");
		}

		return View::make('ask.list');
	}

	// function to show answer of a question ( in the form of ID )
	public function show_answer( $id )
	{
		// if user has not logged in
		if ( !Auth::check() )
		{			
			return Redirect::to("/login");
		}
		
		return View::make('ask.answer');
	}

}
