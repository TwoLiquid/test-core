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
        Schema::create('appearance_case_platform', function (Blueprint $table) {
            $table->bigInteger('appearance_case_id')->unsigned()->index();
            $table->bigInteger('platform_id')->unsigned()->index();

            $table->foreign('appearance_case_id')
                ->references('id')
                ->on('appearance_cases')
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
        Schema::dropIfExists('appearance_case_platform');
    }
};
