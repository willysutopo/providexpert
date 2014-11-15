<?php

class Expert extends Eloquent {
	public function category()
	{
		return $this->belongsTo('Category');
	}

	public function specificQuestions()
	{
		// List of questions specifically set for the expert
		return $this->hasMany('Question', 'specific_expert_id');
	}

	public function answers()
	{
		// List of answers given by this expert
		return $this->hasMany('Answer');
	}

	public function user()
	{
		return $this->belongsTo('User');
	}
}