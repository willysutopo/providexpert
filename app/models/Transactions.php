<?php

class Transactions extends Eloquent {
	protected $table = 'transactions';
	public function user()
	{
		return $this->belongsTo('User', 'user_id');
	}
}
