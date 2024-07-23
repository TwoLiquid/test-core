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
        Schema::connection('mongodb')->table('device_suggestions', function (Blueprint $collection) {
            $collection->id();
            $collection->string('vybe_publish_request_id')->index();
            $collection->string('vybe_change_request_id')->index();
            $collection->integer('device_id');
            $collection->string('suggestion');
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
        Schema::connection('mongodb')->table('device_suggestions', function (Blueprint $collection) {
            $collection->drop();
        });
    }
};

