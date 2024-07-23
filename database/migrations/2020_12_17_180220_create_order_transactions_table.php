<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Running MySQL migration
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() : void
    {
        Schema::create('order_transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('invoice_id')->unsigned()->index();
            $table->bigInteger('method_id')->unsigned()->index();
            $table->string('external_id')->nullable();
            $table->double('amount');
            $table->double('transaction_fee')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('invoice_id')
                ->references('id')
                ->on('order_invoices')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('method_id')
                ->references('id')
                ->on('payment_methods')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() : void
    {
        Schema::dropIfExists('order_transactions');
    }
};
