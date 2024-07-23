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
        Schema::create('invoice_order_item', function (Blueprint $table) {
            $table->bigInteger('invoice_id')->unsigned()->index();
            $table->bigInteger('item_id')->unsigned()->index();

            $table->foreign('invoice_id')
                ->references('id')
                ->on('order_invoices')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('item_id')
                ->references('id')
                ->on('order_items')
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
        Schema::dropIfExists('invoice_order_item');
    }
};
