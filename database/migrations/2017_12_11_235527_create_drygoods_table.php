<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDrygoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drygood_inventories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->decimal('stock', 10, 2)->default(0); 
            $table->integer('reorder_level');
            $table->string('unit_type');
            $table->string('physical_quantity');
            $table->integer('category_id')->unsigned();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });

        Schema::create('drygood_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('produce')->default(0);
            $table->decimal('price', 10, 2);
            $table->decimal('cost', 10, 2)->default(0);
            $table->integer('category_id')->unsinged();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });

        Schema::create('drygood_inventory_product', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('inventory_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->integer('quantity');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
            $table->foreign('product_id')->references('id')->on('drygood_products')->onDelete('cascade');
            $table->foreign('inventory_id')->references('id')->on('drygood_inventories')->onDelete('cascade');
        });

        Schema::create('drygood_stocks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quantity')->unsigned();
            $table->decimal('price', 10, 2);
            $table->date('received');
            $table->date('expiration');
            $table->string('status')->default('NEW');
            $table->integer('inventory_id')->unsigned();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });

        Schema::create('drygood_produce', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id');
            $table->integer('quantity');
            $table->date('date');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });

        Schema::create('drygood_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id');
            $table->string('description');
            $table->string('status');
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
        Schema::dropIfExists('drygood_inventories');
        Schema::dropIfExists('drygood_products');
        Schema::dropIfExists('drygood_inventory_product');
        Schema::dropIfExists('drygood_stocks');
        Schema::dropIfExists('drygood_produce');
        Schema::dropIfExists('drygood_history');
    }
}
