<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
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
            $table->decimal('received')->nullable();
            $table->decimal('issued')->nullable();           
            $table->decimal('received_amount', 8, 2)->default(0);           
            $table->decimal('issued_amount', 8, 2)->default(0);
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
        Schema::dropIfExists('sales');
    }
}
