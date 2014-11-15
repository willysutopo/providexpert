<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterExperts extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('experts', function(Blueprint $table) {
			$table->dropColumn('email');
			$table->dropColumn('password');
			$table->integer('user_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('experts', function(Blueprint $table) {
			$table->dropColumn('user_id');
			$table->string('password', 100);
			$table->string('email', 100);
		});
	}

}
