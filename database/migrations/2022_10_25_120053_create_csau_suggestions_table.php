<?php

use Illuminate\Database\Migrations\Migration;
use MongoDB\Laravel\Schema\Blueprint;
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
        Schema::connection('mongodb')->table('csau_suggestions', function (Blueprint $collection) {
            $collection->id();
            $collection->string('vybe_publish_request_id')->index();
            $collection->string('vybe_change_request_id')->index();
            $collection->integer('category_id');
            $collection->string('category_suggestion');
            $collection->integer('category_status_id');
            $collection->integer('subcategory_id');
            $collection->string('subcategory_suggestion');
            $collection->integer('subcategory_status_id');
            $collection->integer('activity_id');
            $collection->string('activity_suggestion');
            $collection->integer('activity_status_id');
            $collection->integer('unit_id');
            $collection->string('unit_suggestion');
            $collection->integer('unit_duration');
            $collection->integer('unit_status_id');
            $collection->boolean('visible');
            $collection->integer('admin_id')->index();
            $collection->integer('status_id');
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
        Schema::connection('mongodb')->table('csau_suggestions', function (Blueprint $collection) {
            $collection->drop();
        });
    }
};
