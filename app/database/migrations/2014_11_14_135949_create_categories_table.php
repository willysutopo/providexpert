<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('categories', function(Blueprint $table)
		{
			$table->increments('id');			
			$table->string('category_alias', 100);
			$table->string('category_name', 100);
			$table->string('pic_link', 200);
			$table->integer('published');			
			$table->timestamps();
		});

		$statement = "ALTER TABLE categories AUTO_INCREMENT = 50;";
        DB::unprepared($statement);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('categories');
	}

}
