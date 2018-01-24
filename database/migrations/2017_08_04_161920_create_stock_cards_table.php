<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stockcards', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');                   
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')
                    ->references('id')
                    ->on('products')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            $table->string('receipt', 100)->nullable();
            $table->string('reference', 100)->nullable();
            $table->decimal('received')->default(0);
            $table->decimal('issued')->default(0);
            $table->decimal('balance', 8, 0)->default(0);
            $table->string('remarks')->nullable();
            $table->string('status');
            $table->string('created_by');
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
        Schema::dropIfExists('stockcards');
    }
}
