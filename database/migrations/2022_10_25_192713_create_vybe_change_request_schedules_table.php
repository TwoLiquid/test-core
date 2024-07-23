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
        Schema::connection('mongodb')->table('vybe_change_request_schedules', function (Blueprint $collection) {
            $collection->id();
            $collection->string('vybe_change_request_id')->index();
            $collection->timestamp('datetime_from');
            $collection->timestamp('datetime_from');
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
        Schema::connection('mongodb')->table('vybe_change_request_schedules', function (Blueprint $collection) {
            $collection->drop();
        });
    }
};

