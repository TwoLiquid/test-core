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
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->index()->nullable();
            $table->boolean('notification_enable');
            $table->boolean('email_followers_follows_you');
            $table->boolean('email_followers_new_vybe');
            $table->boolean('email_followers_new_event');
            $table->boolean('messages_unread');
            $table->boolean('email_orders_new');
            $table->boolean('email_order_starts');
            $table->boolean('email_order_pending');
            $table->boolean('reschedule_info');
            $table->boolean('review_new');
            $table->boolean('review_waiting');
            $table->boolean('withdrawals_info');
            $table->boolean('email_invitation_info');
            $table->boolean('tickets_new_order');
            $table->boolean('miscellaneous_regarding');
            $table->boolean('platform_followers_follows');
            $table->boolean('platform_followers_new_vybe');
            $table->boolean('platform_followers_new_event');
            $table->boolean('platform_order_starts');
            $table->boolean('platform_invitation_info');
            $table->boolean('news_receive');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() : void
    {
        Schema::dropIfExists('notification_settings');
    }
};
