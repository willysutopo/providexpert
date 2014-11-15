<?php

class Category extends Eloquent {
	public function questions()
	{
		return $this->hasMany('Question');
	}

	public function experts()
	{
		return $this->hasMany('Expert');
	}
}
