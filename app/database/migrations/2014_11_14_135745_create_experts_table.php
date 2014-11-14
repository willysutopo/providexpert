<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpertsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('experts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('expert_name', 200);
			$table->integer('category_id');
			$table->string('expertises', 300);
			$table->string('email', 100);
			$table->string('password', 100);
			$table->string('phone', 100);
			$table->string('address', 200);
			$table->string('pic_link', 200);
			$table->integer('published');
			$table->timestamps();
		});

		$statement = "ALTER TABLE experts AUTO_INCREMENT = 50;";
        DB::unprepared($statement);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('experts');
	}

}
