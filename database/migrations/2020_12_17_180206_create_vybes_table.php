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
        Schema::create('vybes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->index();
            $table->bigInteger('activity_id')->unsigned()->index()->nullable();
            $table->bigInteger('type_id')->unsigned()->index()->nullable();
            $table->bigInteger('period_id')->unsigned()->index()->nullable();
            $table->bigInteger('access_id')->unsigned()->index()->nullable();
            $table->bigInteger('showcase_id')->unsigned()->index()->nullable();
            $table->bigInteger('status_id')->unsigned()->index()->nullable();
            $table->tinyInteger('age_limit_id')->unsigned()->index()->nullable();
            $table->tinyInteger('order_accept_id')->unsigned()->index()->nullable();
            $table->tinyInteger('step_id')->unsigned()->index()->default(1);
            $table->string('access_password')->nullable();
            $table->integer('version')->default(0);
            $table->string('title')->nullable();
            $table->integer('user_count')->nullable();
            $table->integer('order_advance')->nullable();
            $table->double('rating')->default(0)->nullable();
            $table->integer('rating_votes')->default(0)->nullable();
            $table->string('suspend_reason')->nullable();
            $table->json('images_ids')->nullable();
            $table->json('videos_ids')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('activity_id')
                ->references('id')
                ->on('activities')
                ->onUpdate('cascade')
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
        Schema::dropIfExists('vybes');
    }
};
