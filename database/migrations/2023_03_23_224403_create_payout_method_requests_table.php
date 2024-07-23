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
        Schema::connection('mongodb')->table('payout_method_requests', function (Blueprint $collection) {
            $collection->id();
            $collection->integer('method_id')->index();
            $collection->integer('user_id')->index();
            $collection->boolean('shown');
            $collection->integer('request_status_id')->index();
            $collection->integer('toast_message_type_id');
            $collection->string('toast_message_text');
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
        Schema::connection('mongodb')->table('payout_method_requests', function (Blueprint $collection) {
            $collection->drop();
        });
    }
};

