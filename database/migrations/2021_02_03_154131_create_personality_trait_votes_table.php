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
        Schema::create('personality_trait_votes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('personality_trait_id')->unsigned()->index();
            $table->bigInteger('voter_id')->unsigned()->index();

            $table->foreign('personality_trait_id')
                ->references('id')
                ->on('personality_traits')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('voter_id')
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
        Schema::dropIfExists('personality_trait_votes');
    }
};
