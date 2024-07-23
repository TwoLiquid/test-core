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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->index();
            $table->bigInteger('appearance_case_id')->unsigned()->index();
            $table->bigInteger('timeslot_id')->unsigned()->index()->nullable();
            $table->dateTime('datetime_from')->nullable();
            $table->dateTime('datetime_to')->nullable();
            $table->integer('quantity')->default(1);

            $table->foreign('user_id')
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
        Schema::dropIfExists('cart_items');
    }
};

