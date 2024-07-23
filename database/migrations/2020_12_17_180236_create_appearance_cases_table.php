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
        Schema::create('appearance_cases', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('vybe_id')->unsigned()->index();
            $table->bigInteger('appearance_id')->unsigned()->index();
            $table->bigInteger('unit_id')->unsigned()->index()->nullable();
            $table->string('city_place_id')->index()->nullable();
            $table->double('price')->nullable();
            $table->text('description')->nullable();
            $table->boolean('same_location')->default(true)->nullable();
            $table->boolean('enabled')->default(true)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('vybe_id')
                ->references('id')
                ->on('vybes')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('unit_id')
                ->references('id')
                ->on('units')
                ->onUpdate('cascade')
                ->onDelete('set null');

            $table->foreign('city_place_id')
                ->references('place_id')
                ->on('city_places')
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
        Schema::dropIfExists('appearance_cases');
    }
};
