<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductSlidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_slides', function (Blueprint $table) {
            $table->increments('ID');
            $table->string('image', 512)->nullable();
            $table->string('large_image', 512)->nullable();
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
        Schema::dropIfExists('product_slides');
    }
}
