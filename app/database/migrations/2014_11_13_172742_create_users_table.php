<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');			
			$table->string('fullname', 100);
			$table->string('email', 100);
			$table->string('password', 100);
			$table->string('address', 200);
			$table->string('city', 100);			
			$table->string('country', 2);
			$table->string('phone', 20);
			$table->string('role', 10);
			$table->rememberToken();			
			$table->char('status', 1);			
			$table->string('photo', 100);
			$table->string('timezone', 50);
			$table->integer('credits');
			$table->datetime('last_login');
			$table->timestamps();
		});

		$statement = "ALTER TABLE users AUTO_INCREMENT = 20;";
        DB::unprepared($statement);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
