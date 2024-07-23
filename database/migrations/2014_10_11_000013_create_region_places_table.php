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
        Schema::create('region_places', function (Blueprint $table) {
            $table->id();
            $table->string('country_place_id')->index();
            $table->string('place_id')->unique()->index();
            $table->json('name');
            $table->string('code')->nullable();

            $table->foreign('country_place_id')
                ->references('place_id')
                ->on('country_places')
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
        Schema::dropIfExists('region_places');
    }
};