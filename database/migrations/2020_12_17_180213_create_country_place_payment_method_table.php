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
        Schema::create('country_place_payment_method', function (Blueprint $table) {
            $table->string('place_id')->index();
            $table->bigInteger('method_id')->unsigned()->index();
            $table->boolean('excluded')->default(false);

            $table->foreign('place_id')
                ->references('place_id')
                ->on('country_places')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('method_id')
                ->references('id')
                ->on('payment_methods')
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
        Schema::dropIfExists('country_place_payment_method');
    }
};
