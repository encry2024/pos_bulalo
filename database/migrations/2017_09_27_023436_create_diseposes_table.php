<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiseposesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disposes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('inventory_id')->unsigned();
            $table->date('date');
            $table->integer('quantity');
            $table->decimal('cost', 10, 2);
            $table->decimal('total_cost', 10, 2);
            $table->string('reason');
            $table->string('witness');
            $table->string('type');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });

        Schema::create('drygood_disposes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('inventory_id')->unsigned();
            $table->date('date');
            $table->integer('quantity');
            $table->decimal('cost', 10, 2);
            $table->decimal('total_cost', 10, 2);
            $table->string('reason');
            $table->string('witness');
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
        Schema::dropIfExists('disposes');
        Schema::dropIfExists('drygood_disposes');
    }
}
