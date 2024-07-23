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
        Schema::connection('mongodb')->table('vybe_publish_request_appearance_cases', function (Blueprint $collection) {
            $collection->id();
            $collection->string('vybe_publish_request_id')->index();
            $collection->index('appearance_id');
            $collection->double('price');
            $collection->double('previous_price');
            $collection->index('price_status_id');
            $collection->index('unit_id');
            $collection->integer('previous_unit_id');
            $collection->string('unit_suggestion');
            $collection->integer('unit_status_id');
            $collection->text('description');
            $collection->text('previous_description');
            $collection->integer('description_status_id');
            $collection->json('platforms_ids');
            $collection->json('previous_platforms_ids');
            $collection->integer('platforms_status_id');
            $collection->boolean('same_location');
            $collection->string('city_place_id');
            $collection->string('previous_city_place_id');
            $collection->boolean('enabled');
            $collection->integer('city_place_status_id');
            $collection->integer('csau_suggestion_id');
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
        Schema::connection('mongodb')->table('vybe_publish_request_appearance_cases', function (Blueprint $collection) {
            $collection->drop();
        });
    }
};

