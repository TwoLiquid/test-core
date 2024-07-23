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
        Schema::connection('mongodb')->table('user_profile_requests', function (Blueprint $collection) {
            $collection->id();
            $collection->integer('user_id')->index();
            $collection->integer('account_status_id');
            $collection->integer('account_status_status_id');
            $collection->integer('previous_account_status_id');
            $collection->string('username');
            $collection->integer('username_status_id');
            $collection->string('previous_username');
            $collection->string('birth_date');
            $collection->integer('birth_date_status_id');
            $collection->string('previous_birth_date');
            $collection->text('description');
            $collection->integer('description_status_id');
            $collection->text('previous_description');
            $collection->integer('voice_sample_id');
            $collection->integer('voice_sample_status_id');
            $collection->integer('previous_voice_sample_id');
            $collection->integer('avatar_id');
            $collection->integer('avatar_status_id');
            $collection->integer('previous_avatar_id');
            $collection->integer('background_id');
            $collection->integer('background_status_id');
            $collection->integer('previous_background_id');
            $collection->json('images_ids');
            $collection->json('previous_images_ids');
            $collection->json('videos_ids');
            $collection->json('previous_videos_ids');
            $collection->integer('album_status_id');
            $collection->integer('toast_message_type_id');
            $collection->text('toast_message_text');
            $collection->integer('request_status_id');
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
        Schema::connection('mongodb')->table('user_profile_requests', function (Blueprint $collection) {
            $collection->drop();
        });
    }
};

