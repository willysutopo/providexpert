<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('questions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->longText('question');
			$table->integer('category_id');
			$table->integer('asker_id');
			$table->integer('specific_expert_id');
			$table->integer('published');
			$table->timestamps();
		});

		$statement = "ALTER TABLE questions AUTO_INCREMENT = 80;";
        DB::unprepared($statement);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('questions');
	}

}
