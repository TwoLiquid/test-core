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
        Schema::create('alert_profanity_words', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('filter_id')->unsigned()->index();
            $table->string('word');

            $table->foreign('filter_id')
                ->references('id')
                ->on('alert_profanity_filters')
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
        Schema::dropIfExists('alert_profanity_words');
    }
};

