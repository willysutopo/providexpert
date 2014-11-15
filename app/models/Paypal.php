<?php

class Paypal extends Eloquent {

	protected $table = 'paypal_info';
	protected $fillable = array(
		'user_id',
		'token',
		'expired',
		'type',
		'masked',
	);
	public function user()
	{
		return $this->belongsTo('User', 'user_id');
	}
}
