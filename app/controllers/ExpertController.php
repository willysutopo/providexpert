<?php

class ExpertController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
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

	// function to list experts according to category	
	public function show_list( $category )
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

		$arr_experts = array();

		if ( $category == "health")
		{
			$arr_experts = array(				
				"1" => "Dr. Boyke", 
				"2" => "Dr. Bondan", 
				"3" => "Dr. Ronny"
			);
		}
		else
		if ( $category == "property")
		{
			$arr_experts = array(
				"1" => "Budi", 
				"2" => "Andi",
			);
		}
		else
		if ( $category == "food")
		{
			$arr_experts = array(
				"1" => "Gordon Ramsey", 
				"2" => "Rudi Choirudin",
			);
		}
		else
		if ( $category == "love")
		{
			$arr_experts = array(
				"1" => "Dr. Phil", 				
			);
		}
		else
		if ( $category == "education")
		{
			$arr_experts = array(
				"1" => "Kak Seto", 
			);
		}

		return View::make('experts.list')->withCategory($category)->withImage($category_image)->withExperts($arr_experts)->withCategories($arr_categories);
	}
}
