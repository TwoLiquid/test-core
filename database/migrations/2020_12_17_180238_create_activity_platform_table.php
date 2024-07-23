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
        Schema::create('activity_platform', function (Blueprint $table) {
            $table->bigInteger('activity_id')->unsigned()->index();
            $table->bigInteger('platform_id')->unsigned()->index();

            $table->foreign('activity_id')
                ->references('id')
                ->on('activities')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('platform_id')
                ->references('id')
                ->on('platforms')
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
        Schema::dropIfExists('activity_platform');
    }
};
