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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('auth_id')->unsigned()->index();
            $table->bigInteger('gender_id')->unsigned()->index()->nullable();
            $table->bigInteger('currency_id')->unsigned()->index()->nullable();
            $table->bigInteger('language_id')->unsigned()->index()->nullable();
            $table->bigInteger('label_id')->unsigned()->index()->nullable();
            $table->bigInteger('state_status_id')->unsigned()->index()->nullable();
            $table->bigInteger('account_status_id')->unsigned()->index()->nullable();
            $table->bigInteger('verification_status_id')->unsigned()->index()->nullable();
            $table->bigInteger('referred_user_id')->unsigned()->index()->nullable();
            $table->bigInteger('suspend_admin_id')->unsigned()->index()->nullable();
            $table->bigInteger('timezone_id')->unsigned()->index()->nullable();
            $table->bigInteger('theme_id')->unsigned()->index()->default(1);
            $table->string('current_city_place_id')->index()->nullable();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('email_verify_token')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('last_email_changed_at')->nullable();
            $table->dateTime('birth_date')->nullable();
            $table->mediumText('description')->nullable();
            $table->string('invitation_code')->nullable();
            $table->text('suspend_reason')->nullable();
            $table->boolean('streamer_badge')->default(false);
            $table->boolean('streamer_milestone')->default(false);
            $table->boolean('verified_celebrity')->default(false);
            $table->boolean('verified_partner')->default(false);
            $table->boolean('verify_blocked')->default(false);
            $table->boolean('verification_suspended')->default(false);
            $table->boolean('hide_gender')->default(false);
            $table->boolean('hide_age')->default(false);
            $table->boolean('hide_reviews')->default(false);
            $table->boolean('hide_location')->default(false);
            $table->boolean('receive_news')->default(true);
            $table->boolean('enable_fast_order')->default(true);
            $table->boolean('top_vybers')->default(true);
            $table->boolean('vpn_used')->default(false);
            $table->integer('avatar_id')->nullable();
            $table->integer('background_id')->nullable();
            $table->integer('voice_sample_id')->nullable();
            $table->json('images_ids')->nullable();
            $table->json('videos_ids')->nullable();
            $table->string('password_reset_token')->nullable();
            $table->integer('login_attempts_left')->default(5);
            $table->timestamp('next_login_attempt_at')->nullable();
            $table->integer('email_attempts_left')->default(5);
            $table->timestamp('next_email_attempt_at')->nullable();
            $table->integer('password_attempts_left')->default(5);
            $table->timestamp('next_password_attempt_at')->nullable();
            $table->timestamp('signed_up_at')->nullable();
            $table->timestamp('temporary_deleted_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('referred_user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('set null')
                ->onDelete('set null');

            $table->foreign('suspend_admin_id')
                ->references('id')
                ->on('users')
                ->onUpdate('set null')
                ->onDelete('set null');

            $table->foreign('timezone_id')
                ->references('id')
                ->on('timezones')
                ->onUpdate('set null')
                ->onDelete('set null');

            $table->foreign('current_city_place_id')
                ->references('place_id')
                ->on('city_places')
                ->onUpdate('set null')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() : void
    {
        Schema::dropIfExists('users');
    }
};
