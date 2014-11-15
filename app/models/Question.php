<?php

class Question extends Eloquent {
    public function category()
    {
    	return $this->belongsTo('Category');
    }

    public function asker()
    {
    	return $this->belongsTo('User', 'asker_id');
	}

	public function specificExpert()
	{
		return $this->belongsTo('Expert', 'specific_expert_id');
	}

	public function answers()
	{
		return $this->hasMany('Answer');
	}

}