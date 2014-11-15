<?php

class ExpertController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$arr_categories = Category::all();

		return View::make('experts.index')->withCategories( $arr_categories );
	}

	// function to list experts according to category	
	public function show_list( $category )
	{
		$arr_category = Category::where('category_alias', $category)->first();
		$category_id = $arr_category->id;
		$arr_experts = Expert::where('category_id', $category_id)->get();

		return View::make('experts.list')->withCategory($arr_category)->withExperts($arr_experts);
	}
}
