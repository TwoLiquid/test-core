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
        Schema::create('country_places', function (Blueprint $table) {
            $table->id();
            $table->string('place_id')->unique()->index();
            $table->string('code');
            $table->json('name');
            $table->json('official_name');
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->boolean('has_regions')->default(false);
            $table->boolean('excluded')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() : void
    {
        Schema::dropIfExists('country_places');
    }
};
