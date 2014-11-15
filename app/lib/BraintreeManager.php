<?php
//
// Braintree Manager
//
/*
	testing CC Number

	4111111111111111
	4012888888881881

	BRAINTREE PHP SDK EXTREMLY SIMPLE AND EASY FOR USAGE
*/

class BraintreeManager{

	protected $config = array(
		'merchantId'	=> "hkjcnw6bcqwcxxbx",
		'publicKey'		=> "2bgz27xf24295zwz",
		'privateKey'	=> "b7af749f44e1a07e231ced749043a434",
	);

	public function __construct()
	{
		//
		// define Braintree
		//
		Braintree_Configuration::environment('sandbox');
		Braintree_Configuration::merchantId($this->config['merchantId']);
		Braintree_Configuration::publicKey($this->config['publicKey']);
		Braintree_Configuration::privateKey($this->config['privateKey']);		
	}

	//
	// register user with same ID from Our DB
	//
	public function createUser($user_id, $firstName, $lastName)
	{
		$result = Braintree_Customer::create(array(
		    'id' => $user_id,
		    'firstName' => $firstName,
		    'lastName' => $lastName,
		));

		return $result->success;
	}

	public function updateUser($user_id, $firstName, $lastName)
	{
		$result = Braintree_Customer::update($user_id, array(
		    'firstName' => $firstName,
		    'lastName' => $lastName,
		));

		return $result->success;
	}	

	//
	// if user create CC, update to braintree
	//
	public function createCC($user_id, $cc_num, $cvv_num, $exp)
	{
		$result = Braintree_CreditCard::create(array(
		    'customerId' => $user_id,
		    'cvv' => $cvv_num,
		    'number' => $cc_num,
		    'expirationDate' => $exp,
		    'options' => array(
		        'verifyCard' => true
		    )
		));

		return $result;
	}	

	public function updateCC($user_id, $cc_num, $cvv_num, $exp)
	{
		$result = Braintree_Customer::update($user_id, array(
		    'creditCard' => array(
		        'number' => $cc_num,
		        'cvv' => $cvv_num,
		        'expirationDate' => $exp,
		        'options' => array(
		            'verifyCard' => true
		        )
		    )
		));	

		return $result;
	}

	public function sale($user_id, $amount)
	{
		$result = Braintree_Transaction::sale(array(
		  'amount' => $amount,
		  'customerId' => $user_id,
		));

		return $result;
	}
}
