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
        Schema::create('city_places', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('timezone_id')->unsigned()->index()->nullable();
            $table->string('place_id')->unique()->index();
            $table->json('name');
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();

            $table->foreign('timezone_id')
                ->references('id')
                ->on('timezones')
                ->onUpdate('set null')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() : void
    {
        Schema::dropIfExists('city_places');
    }
};
