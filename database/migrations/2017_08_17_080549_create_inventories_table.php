<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateinventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
        //
        Schema::create('inventories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('inventory_id');
            $table->decimal('stock', 10,2)->default(0);
            $table->integer('reorder_level');
            $table->string('unit_type');
            $table->string('supplier');
            $table->string('physical_quantity');
            $table->integer('category_id')->unsigned();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });

        Schema::create('other_inventories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
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
        Schema::dropIfExists('categories');
        Schema::dropIfExists('inventories');
    }
}
