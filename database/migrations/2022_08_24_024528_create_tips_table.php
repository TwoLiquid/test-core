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
        Schema::create('tips', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('item_id')->unsigned()->index();
            $table->bigInteger('method_id')->unsigned()->index();
            $table->bigInteger('buyer_id')->unsigned()->index();
            $table->bigInteger('seller_id')->unsigned()->index();
            $table->double('amount')->nullable();
            $table->double('amount_earned')->nullable();
            $table->double('amount_tax')->nullable();
            $table->double('amount_total')->nullable();
            $table->double('handling_fee')->nullable();
            $table->double('payment_fee')->nullable();
            $table->double('payment_fee_tax')->nullable();
            $table->text('comment')->nullable();
            $table->string('hash')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('item_id')
                ->references('id')
                ->on('order_items')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('method_id')
                ->references('id')
                ->on('payment_methods')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('buyer_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('seller_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('tips');
    }
};

