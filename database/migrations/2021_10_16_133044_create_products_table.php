<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('store_product_id')->index();
            $table->json('images')->nullable();
            $table->string('sku')->nullable()->index();
            $table->string('gtin')->nullable()->index();
            $table->string('mpn')->nullable()->index();
            $table->decimal('price', 10, 4)->default(0);
            $table->decimal('sale_price', 10, 4)->default(0)->nullable();
            $table->unsignedInteger('quantity')->default(0)->nullable();
            $table->string('brand')->nullable();
            $table->unsignedBigInteger('item_group_id')->nullable();
            $table->string('shipping')->nullable();
            $table->float('weight')->nullable();
            $table->float('width')->nullable();
            $table->float('length')->nullable();
            $table->float('height')->nullable();
            $table->unsignedTinyInteger('status')->default(1);
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'store_product_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
