<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMainPageSpecificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('main_page_specification', function (Blueprint $table) {
            $table->string('logo_splash', 255)->default("https://api.backino.net/red-apple/logo_splash.png");
            $table->string('logo', 255)->default("https://api.backino.net/red-apple/logo.png");
            $table->string('title', 255)->default("سیب سرخ");
            $table->longText('desc')->nullable();
            $table->string('splash_bgcolor')->default("#1a1a1a");
            $table->string('splash_fontcolor')->default("#ffffff");
            $table->string('toolbar_bgcolor')->default("#d40046");
            $table->string('toolbar_fontcolor')->default("#ffffff");
            $table->boolean('show_instagram_button')->default(true);
            $table->string('instagram_page_url', 255)->default("https://www.instagram.com/partodesign/");
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
        Schema::dropIfExists('main_page_specification');
    }
}
