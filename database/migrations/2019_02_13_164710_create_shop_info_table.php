<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_info', function (Blueprint $table) {
            $table->string('logo_splash')->nullable();
            $table->string('logo')->nullable();
            $table->string('title')->nullable();
            $table->string('desc')->nullable();
            $table->string('splash_bgcolor')->nullable();
            $table->string('splash_fontcolor')->nullable();
            $table->string('toolbar_bgcolor')->nullable();
            $table->string('toolbar_fontcolor')->nullable();
            $table->boolean('show_instagram_button')->default(true);
            $table->string('instagram_page_url')->nullable();
            $table->boolean('show_category_button')->default(true);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_info');
    }
}
