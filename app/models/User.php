<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Zizaco\Entrust\HasRole;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use HasRole;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

	protected $fillable = array(
		'fullname', 'email', 'password', 'address', 'city', 'country', 'phone', 'remember_token', 'status', 'photo', 'timezone', 'credits', 'last_login',
	);

	public function askedQuestions()
	{
		return $this->hasMany('Question', 'asker_id');
	}

	public function transactions()
	{
		return $this->hasMany('Transactions', 'user_id');
	}

	public function paypal()
	{
		return $this->hasOne('Paypal', 'user_id');
	}

	public function expert()
	{
		return $this->hasOne('Expert');
	}
}