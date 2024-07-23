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
        Schema::create('alert_profanity_filters', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('alert_id')->unsigned()->index();
            $table->boolean('replace')->default(true);
            $table->string('replace_with')->default('*******');
            $table->boolean('hide_messages')->default(true);
            $table->boolean('bad_words')->default(true);

            $table->foreign('alert_id')
                ->references('id')
                ->on('alerts')
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
        Schema::dropIfExists('alert_profanity_filters');
    }
};
