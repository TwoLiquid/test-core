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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id')->unsigned()->index();
            $table->bigInteger('vybe_id')->unsigned()->index();
            $table->bigInteger('seller_id')->unsigned()->index();
            $table->bigInteger('appearance_case_id')->unsigned()->index();
            $table->bigInteger('timeslot_id')->unsigned()->index();
            $table->bigInteger('status_id')->unsigned()->index();
            $table->bigInteger('previous_status_id')->unsigned()->index()->nullable();
            $table->bigInteger('payment_status_id')->unsigned()->index();
            $table->integer('vybe_version');
            $table->double('price');
            $table->integer('quantity')->default(1);
            $table->double('handling_fee')->nullable();
            $table->double('amount_earned');
            $table->double('amount_tax')->nullable();
            $table->double('amount_total');
            $table->dateTime('expired_at')->nullable();
            $table->dateTime('accepted_at')->nullable();
            $table->dateTime('finished_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('vybe_id')
                ->references('id')
                ->on('vybes')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('seller_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('appearance_case_id')
                ->references('id')
                ->on('appearance_cases')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('timeslot_id')
                ->references('id')
                ->on('timeslots')
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
        Schema::dropIfExists('order_items');
    }
};
