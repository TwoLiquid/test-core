<?php

use MongoDB\Laravel\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
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
        Schema::connection('mongodb')->table('vybe_appearance_case_supports', function (Blueprint $collection) {
            $collection->id();
            $collection->integer('appearance_case_id')->index();
            $collection->string('unit_suggestion');
            $collection->string('city_place_id')->index();
            $collection->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() : void
    {
        Schema::connection('mongodb')->table('vybe_appearance_case_supports', function (Blueprint $collection) {
            $collection->drop();
        });
    }
};

