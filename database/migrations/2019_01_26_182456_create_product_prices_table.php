<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_prices', function (Blueprint $table) {
            $table->increments('ID');
            $table->string('colorID')->nullable();
            $table->string('txtcolorcode')->nullable();
            $table->string('colortitle')->nullable();
            $table->string('colorcode')->nullable();
            $table->string('garrantytitle')->nullable();
            $table->string('pricetxt')->nullable();
            $table->integer('price')->nullable();
            $table->string('oldpricetxt')->nullable();
            $table->integer('discount')->nullable();
            $table->unsignedInteger('product_ID')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_prices');
    }
}
