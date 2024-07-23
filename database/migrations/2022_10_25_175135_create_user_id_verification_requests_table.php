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
        Schema::connection('mongodb')->table('user_id_verification_requests', function (Blueprint $collection) {
            $collection->id();
            $collection->integer('user_id')->index();
            $collection->integer('verification_status_id');
            $collection->integer('verification_status_status_id');
            $collection->integer('previous_verification_status_id');
            $collection->boolean('shown');
            $collection->integer('request_status_id');
            $collection->integer('toast_message_type_id');
            $collection->text('toast_message_text');
            $collection->integer('admin_id')->index();
            $collection->integer('language_id')->index();
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
        Schema::connection('mongodb')->table('user_id_verification_requests', function (Blueprint $collection) {
            $collection->drop();
        });
    }
};

