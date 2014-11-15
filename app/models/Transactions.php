<?php

class Transactions extends Eloquent {
	protected $table = 'transactions';
	protected $fillable = array(
		'user_id',
		'trx_id',
		'status',
		'amount',
	);
	public function user()
	{
		return $this->belongsTo('User', 'user_id');
	}
}
