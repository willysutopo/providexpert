<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaypalInfo extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('paypal_info', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id'); 
			$table->string('token', 50); // gku123
			$table->string('expired', 50); // 09/2014
			$table->string('type', 50); // Visa, Master
			$table->string('masked', 50); // 	411111******1111
			
			$table->timestamps();
		});
	}

	/** 
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('paypal_info');
	}

}
