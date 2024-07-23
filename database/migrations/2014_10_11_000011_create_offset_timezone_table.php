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
        Schema::create('offset_timezone', function (Blueprint $table) {
            $table->bigInteger('offset_id')->unsigned()->index();
            $table->bigInteger('timezone_id')->unsigned()->index();
            $table->boolean('is_dst')->default(false);

            $table->foreign('offset_id')
                ->references('id')
                ->on('timezone_offsets')
                ->onUpdate('cascade')
                ->onDelete('cascade');

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
        Schema::dropIfExists('offset_timezone');
    }
};
