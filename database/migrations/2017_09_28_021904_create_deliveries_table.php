<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commissary_deliveries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->unsigned();
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->string('type');
            $table->string('status')->default('NOT RECEIVED');
            $table->date('date');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });

        Schema::create('drygood_deliveries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->unsigned();
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->string('status')->default('NOT RECEIVED');
            $table->string('deliver_to');
            $table->date('date');
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
        Schema::dropIfExists('commissary_deliveries');
        Schema::dropIfExists('drygood_deliveries');
    }
}
