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
        Schema::connection('mongodb')->table('order_item_finish_requests', function (Blueprint $collection) {
            $collection->id();
            $collection->integer('item_id')->index();
            $collection->integer('buyer_id')->index();
            $collection->integer('seller_id')->index();
            $collection->integer('opening_id')->index();
            $collection->integer('closing_id')->index()->nullable();
            $collection->integer('initiator_id')->index()->nullable();
            $collection->integer('from_order_item_status_id');
            $collection->integer('to_order_item_status_id')->nullable();
            $collection->integer('action_id')->nullable();
            $collection->integer('request_status_id')->index();
            $collection->timestamp('from_request_datetime');
            $collection->timestamp('to_request_datetime');
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
        Schema::connection('mongodb')->table('order_item_finish_requests', function (Blueprint $collection) {
            $collection->drop();
        });
    }
};

