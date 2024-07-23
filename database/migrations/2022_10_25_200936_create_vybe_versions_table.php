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
        Schema::connection('mongodb')->table('vybe_versions', function (Blueprint $collection) {
            $collection->id();
            $collection->integer('vybe_id')->index();
            $collection->integer('number');
            $collection->string('type');
            $collection->string('title');
            $collection->string('category');
            $collection->string('subcategory');
            $collection->string('activity');
            $collection->json('devices');
            $collection->integer('vybe_handling_fee');
            $collection->string('period');
            $collection->integer('user_count');
            $collection->json('appearance_cases');
            $collection->json('schedules');
            $collection->integer('order_advance');
            $collection->json('images_ids');
            $collection->json('videos_ids');
            $collection->string('access');
            $collection->string('showcase');
            $collection->string('order_accept');
            $collection->string('age_limit');
            $collection->string('status');
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
        Schema::connection('mongodb')->table('vybe_versions', function (Blueprint $collection) {
            $collection->drop();
        });
    }
};

