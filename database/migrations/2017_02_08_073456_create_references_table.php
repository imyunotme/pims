<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReferencesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('references', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('company',100)->nullable();
			$table->string('firstname',100)->nullable();
			$table->string('middlename',50)->nullable();
			$table->string('lastname',50)->nullable();
            $table->string('address')->nullable();
            $table->string('contact')->nullable();
            $table->string('email',100)->nullable();
			// supplier or customers
			$table->string('type');
			// items bought or sold
			$table->string('description')->nullable();
			$table->string('status')->nullable();
			$table->timestamps();
			$table->softDeletes();		
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('references');
	}

}
