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
        Schema::connection('mongodb')->table('payout_method_request_fields', function (Blueprint $collection) {
            $collection->id();
            $collection->string('request_id')->index();
            $collection->integer('field_id')->index();
            $collection->json('value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() : void
    {
        Schema::connection('mongodb')->table('payout_method_request_fields', function (Blueprint $collection) {
            $collection->drop();
        });
    }
};

