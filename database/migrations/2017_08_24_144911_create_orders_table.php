<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('transaction_no');
            $table->decimal('cash', 10, 2);
            $table->decimal('change', 10, 2);
            $table->decimal('payable', 10, 2);
            $table->decimal('discount', 10, 2);
            $table->string('type');
            $table->integer('table_no');
            $table->string('status');
            $table->integer('user_id')->unsigned();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
