<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments("ID" );
            $table->string("fullname" );
            $table->string("city" );
            $table->string("province" );
            $table->longText("address" );
            $table->string("postalcode" );
            $table->string("phone" )->nullable();
            $table->string("mobile" );
            $table->string("areacode" );
            $table->boolean("selected" )->default(false);
            $table->string("latitude")->nullable();
            $table->string("longitude")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
    }
}
