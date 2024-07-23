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
        Schema::connection('mongodb')->table('vybe_change_requests', function (Blueprint $collection) {
            $collection->id();
            $collection->integer('vybe_id')->index();
            $collection->string('title');
            $collection->string('previous_title');
            $collection->integer('title_status_id');
            $collection->integer('category_id');
            $collection->integer('previous_category_id');
            $collection->string('category_suggestion');
            $collection->integer('category_status_id');
            $collection->integer('subcategory_id');
            $collection->integer('previous_subcategory_id');
            $collection->string('subcategory_suggestion');
            $collection->integer('subcategory_status_id');
            $collection->integer('activity_id');
            $collection->integer('previous_activity_id');
            $collection->string('activity_suggestion');
            $collection->integer('activity_status_id');
            $collection->json('devices_ids');
            $collection->json('previous_devices_ids');
            $collection->string('device_suggestion');
            $collection->integer('devices_status_id');
            $collection->integer('period_id');
            $collection->integer('previous_period_id');
            $collection->integer('period_status_id');
            $collection->integer('user_count');
            $collection->integer('previous_user_count');
            $collection->integer('user_count_status_id');
            $collection->integer('type_id');
            $collection->integer('previous_type_id');
            $collection->integer('schedules_status_id');
            $collection->integer('order_advance');
            $collection->integer('previous_order_advance');
            $collection->integer('order_advance_status_id');
            $collection->json('images_ids');
            $collection->json('previous_images_ids');
            $collection->json('videos_ids');
            $collection->json('previous_videos_ids');
            $collection->integer('access_id');
            $collection->integer('previous_access_id');
            $collection->integer('access_status_id');
            $collection->integer('showcase_id');
            $collection->integer('previous_showcase_id');
            $collection->integer('showcase_status_id');
            $collection->integer('order_accept_id');
            $collection->integer('previous_order_accept_id');
            $collection->integer('order_accept_status_id');
            $collection->integer('age_limit_id');
            $collection->integer('status_id');
            $collection->integer('previous_status_id');
            $collection->integer('status_status_id');
            $collection->integer('toast_message_type_id');
            $collection->text('toast_message_text');
            $collection->integer('request_status_id');
            $collection->boolean('shown');
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
        Schema::connection('mongodb')->table('vybe_change_requests', function (Blueprint $collection) {
            $collection->drop();
        });
    }
};
