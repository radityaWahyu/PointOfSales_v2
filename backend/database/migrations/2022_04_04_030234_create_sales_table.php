<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('sales_code', 10);
            $table->foreignUuid('customer_id')->contstrained('customers')->onDelete('restrict')->onUpdate('cascade');
            $table->double('total_pay', 9, 2);
            $table->double('paid', 9, 2);
            $table->double('change', 9, 2);
            $table->enum('type', ['cash', 'credit']);
            $table->date('due_date')->nullable();
            $table->double('debt_amount', 9, 2)->nullable()->default(0);
            $table->foreignUuid('user_id')->nullable(); //->constrained('users')->onDelete('restrict')->onUpdate('cascade');
            $table->boolean('is_pending')->default(false);
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
        Schema::dropIfExists('sales');
    }
}
