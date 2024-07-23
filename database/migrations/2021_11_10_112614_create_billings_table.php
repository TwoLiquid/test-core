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
        Schema::create('billings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->index();
            $table->string('country_place_id')->index();
            $table->string('region_place_id')->index()->nullable();
            $table->string('phone_code_country_place_id')->index()->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('business_info')->default(false);
            $table->string('company_name')->nullable();
            $table->string('vat_number')->nullable();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('country_place_id')
                ->references('place_id')
                ->on('country_places')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('region_place_id')
                ->references('place_id')
                ->on('region_places')
                ->onUpdate('cascade')
                ->onDelete('set null');

            $table->foreign('phone_code_country_place_id')
                ->references('place_id')
                ->on('country_places')
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
        Schema::dropIfExists('billings');
    }
};
