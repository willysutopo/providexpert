<?php

class Answer extends Eloquent {
	public function question()
	{
		return $this->belongsTo('Question');
	}

	public function answeredBy()
	{
		return $this->belongsTo('Expert');
	}
}