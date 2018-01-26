<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCommissary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commissary_inventories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('inventory_id');
            $table->decimal('stock', 10,2)->default(0); 
            $table->integer('reorder_level');
            $table->string('unit_type');
            $table->string('physical_quantity');
            $table->string('supplier');
            $table->integer('category_id')->unsigned();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });

        Schema::create('commissary_other_inventories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });

        Schema::create('commissary_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('produce')->default(0);
            $table->decimal('cost', 10, 2)->default(0);
            $table->integer('category_id')->unsinged();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });

        Schema::create('commissary_inventory_product', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('inventory_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->decimal('quantity', 10, 2);
            $table->string('unit_type');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
            $table->foreign('product_id')->references('id')->on('commissary_products')->onDelete('cascade');
            $table->foreign('inventory_id')->references('id')->on('commissary_inventories')->onDelete('cascade');
        });

        Schema::create('commissary_stocks', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('quantity', 10, 2)->unsigned();
            $table->decimal('price', 10, 2);
            $table->date('received');
            $table->date('expiration');
            $table->string('status')->default('NEW');
            $table->integer('inventory_id')->unsigned();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });

        Schema::create('commissary_produce', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id');
            $table->integer('quantity');
            $table->date('date');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });

        Schema::create('commissary_history', function (Blueprint $table) {
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
       Schema::dropIfExists('commissary_inventories');
       Schema::dropIfExists('commissary_products');
       Schema::dropIfExists('commissary_inventory_product');
       Schema::dropIfExists('commissary_stocks');
       Schema::dropIfExists('commissary_produce');
       Schema::dropIfExists('commissary_history');
    }
}
