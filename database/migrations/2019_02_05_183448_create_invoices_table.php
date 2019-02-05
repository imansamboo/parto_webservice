<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('ID');
            $table->unsignedInteger('address_ID')->default(0);
            $table->unsignedInteger('user_id')->default(0);
            $table->integer('price')->default(0);
            $table->string('date', 128)->nullable();
            $table->enum('status', ['تحویل داده شده', 'پرداخت ناموفق', 'پرداخت موفق', 'درانتظار پرداخت'])->default('درانتظار پرداخت');
            $table->string('trackingcode')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
