<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('code')->nullable();

			// references supply and may contain
			// the following items:
			//		
			$table->integer('supply_id')->unsigned()->nullable();
			$table->foreign('supply_id')
					->references('id')
					->on('supplies')
					->onUpdate('cascade')
					->onDelete('cascade');
			$table->integer('category_id')->unsigned()->nullable();
			$table->foreign('category_id')
					->references('id')
					->on('categories')
					->onUpdate('cascade')
					->onDelete('cascade');
			$table->integer('unit_id')->unsigned()->nullable();
			$table->foreign('unit_id')
					->references('id')
					->on('units')
					->onUpdate('cascade')
					->onDelete('cascade');
            $table->decimal('cost',8,2)->default(0);
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
		Schema::dropIfExists('products');
	}

}
