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
        Schema::create('vybe_rating_votes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('vybe_id')->unsigned()->index();
            $table->bigInteger('user_id')->unsigned()->index();
            $table->integer('rating')->unsigned();

            $table->foreign('vybe_id')
                ->references('id')
                ->on('vybes')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('user_id')
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
        Schema::dropIfExists('vybe_rating_votes');
    }
};
