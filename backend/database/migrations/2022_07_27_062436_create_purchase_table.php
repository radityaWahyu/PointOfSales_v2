<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('purchase_code', 10);
            $table->foreignUuid('supplier_id')->contstrained('suppliers')->onDelete('restrict')->onUpdate('cascade');
            $table->double('total_pay', 9, 2);
            $table->double('paid', 9, 2);
            $table->double('change', 9, 2);
            $table->enum('type', ['cash', 'credit']);
            $table->date('due_date')->nullable();
            $table->double('debt_amount', 9, 2)->nullable()->default(0);
            $table->foreignUuid('user_id')->nullable(); //->constrained('users')->onDelete('restrict')->onUpdate('cascade');
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
        Schema::dropIfExists('purchase');
    }
}
