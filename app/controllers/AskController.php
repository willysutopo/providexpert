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

		$arr_categories = Category::all();

		return View::make('ask.index')->withCategories( $arr_categories );
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
		$rules = [
			'question' => ['required'],
		];

		$category = Input::get('category');

		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails())
		{
			return Redirect::route('ask.question',[$category])->withErrors($validator)->withInput();
		}

		$question = addslashes(Input::get('question'));
		$category_id = Input::get('category_id');
		$asker_id = Auth::user()->id;
		$specific_expert_id = Input::get('expert_id');

		$data = new Question();
		$data->question = $question;
		$data->category_id = $category_id;
		$data->asker_id = $asker_id;
		$data->specific_expert_id = $specific_expert_id;
		$data->published = 1;
		$data->save();

		return Redirect::route('ask.question',[$category])->withMessage('New question saved');
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

		$arr_category = Category::where('category_alias', $category)->first();
		$category_id = $arr_category->id;
		$arr_experts = Expert::where('category_id', $category_id)->get();

		$experts = array();
		$experts["0"] = "All Experts";

		foreach( $arr_experts as $expert )
		{
			$experts[$expert->id] = $expert->expert_name . ' ( '. ( $expert->expertises ) .' )';
		}

		return View::make('ask.question')->withCategory($arr_category)->withExperts($experts);
	}

	// function to show questions list of currently logged-in user
	public function question_list()
	{
		// if user has not logged in
		if ( !Auth::check() )
		{			
			return Redirect::to("/login");
		}

		$categories = Category::all();

		$questions = DB::table('questions')
			->select(DB::raw('count(answers.id) as answer_count, questions.id, questions.question, questions.category_id, categories.category_alias, categories.category_name, answers.updated_at as answer_updated_at'))
			->join('categories', 'categories.id', '=', 'questions.category_id')
			->leftJoin('answers', 'answers.question_id', '=', 'questions.id')
			->where('questions.published', 1)
			->where('questions.asker_id', Auth::user()->id)			
			->orderBy('questions.updated_at', 'desc')
			->groupBy('questions.id')
			->groupBy('questions.question')
			->groupBy('questions.category_id')
			->groupBy('categories.category_alias')
			->groupBy('categories.category_name')
			->groupBy('answers.updated_at')
			->get();		

		return View::make('ask.list')->withQuestions($questions)->withCategories($categories);
	}

	// function to show form for replying question from expert
	public function reply_question( $id )
	{
		// if user has not logged in
		if ( !Auth::check() )
		{			
			return Redirect::to("/login");
		}

		$question = Question::where('id', $id)->first();

		return View::make('ask.reply')->withQuestion( $question );
	}

	public function doReply()
	{
		$rules = [
			'reply' => ['required'],
		];

		$question_id = Input::get('question_id');

		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails())
		{
			return Redirect::route('ask.reply',[$question_id])->withErrors($validator)->withInput();
		}

		$reply = addslashes(Input::get('reply'));
		$expert = Expert::where('user_id', Auth::user()->id)->first();
		$expert_id = $expert->id;

		$data = new Answer();
		$data->answer = $reply;
		$data->question_id = $question_id;
		$data->expert_id = $expert_id;
		$data->published = 1;
		$data->save();

		return Redirect::route('ask.reply',[$question_id])->withMessage('Your reply has been saved.');
	}

	// function to show answer of a question ( in the form of ID )
	public function show_answer( $id )
	{
		// if user has not logged in
		if ( !Auth::check() )
		{
			return Redirect::to("/login");
		}

		$question = Question::where('id', $id)->first();
		$answers = DB::table('answers')
			->select(DB::raw('answers.answer, experts.expert_name, experts.updated_at'))
			->join('experts', 'experts.id', '=', 'answers.expert_id')
			->where('answers.question_id', $id)
			->where('answers.published', 1)
			->orderBy('updated_at', 'desc')
			->get();		
		
		return View::make('ask.answer')->withQuestion( $question )->withAnswers( $answers );
	}

}
