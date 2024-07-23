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
        Schema::create('timezone_time_changes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('timezone_id')->unsigned()->index();
            $table->boolean('to_dst');
            $table->dateTime('started_at');
            $table->dateTime('completed_at');
            $table->timestamps();

            $table->foreign('timezone_id')
                ->references('id')
                ->on('timezones')
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
        Schema::dropIfExists('timezone_time_changes');
    }
};
