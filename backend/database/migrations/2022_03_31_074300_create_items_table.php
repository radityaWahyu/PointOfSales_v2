<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('barcode', 10)->unique()->nullable();
            $table->string('name', 200);
            $table->foreignUuid('category_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->foreignUuid('unit_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->double('purchase_price', 9, 2);
            $table->double('selling_price', 9, 2);
            $table->integer('first_stock');
            $table->integer('stock');
            $table->integer('min_stock');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
