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
        Schema::connection('mongodb')->table('vybe_supports', function (Blueprint $collection) {
            $collection->id();
            $collection->integer('vybe_id')->index();
            $collection->integer('category_id');
            $collection->string('category_suggestion');
            $collection->integer('subcategory_id');
            $collection->string('subcategory_suggestion');
            $collection->string('activity_suggestion');
            $collection->string('device_suggestion');
            $collection->json('devices_ids');
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
        Schema::connection('mongodb')->table('vybe_supports', function (Blueprint $collection) {
            $collection->drop();
        });
    }
};

