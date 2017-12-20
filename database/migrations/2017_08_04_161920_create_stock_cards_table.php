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
            $table->string('user_id');
            $table->date('date');                   
            $table->string('stocknumber');
            $table->foreign('stocknumber')
                    ->references('stocknumber')
                    ->on('supplies')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            $table->string('supplier',100)->nullable();
            $table->decimal('balance',8,0)->default(0); 
            $table->string('reference',100)->nullable();
            $table->string('receipt')->nullable();             
            $table->integer('receivedquantity')->default(0);
            $table->decimal('receivedunitprice')->default(0);
            $table->integer('issuedquantity')->default(0);
            $table->decimal('issuedunitprice')->default(0);
            $table->decimal('balancequantity',8,0)->default(0); 
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
