<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->increments('ID');
            $table->string('title')->nullable();
            $table->string('type')->nullable();
            $table->string('more_button_text')->nullable();
            $table->string('image', 255)->nullable();
            $table->integer('expire_date')->nullable();
            $table->string('target')->nullable();
            $table->string('targetID')->nullable();
            $table->unsignedInteger('page_ID')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sections');
    }
}
