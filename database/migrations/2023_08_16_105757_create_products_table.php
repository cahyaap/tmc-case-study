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
            $table->uuid('id')->primary();
            $table->string('sku');
            $table->string('name');
            $table->unsignedBigInteger('price');
            $table->integer('stock')->default(0);
            $table->foreignUuid('categoryId')->nullable();
            $table->index(['sku', 'name', 'price', 'stock', 'categoryId']);
            $table->timestamp('createdAt')->default(null)->nullable();
            $table->timestamp('updatedAt')->default(null)->nullable();
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
