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
        Schema::create('payment_method_fields', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('method_id')->unsigned()->index();
            $table->bigInteger('type_id')->unsigned()->index();
            $table->json('title');
            $table->json('placeholder');
            $table->json('tooltip')->nullable();

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
        Schema::dropIfExists('payment_method_fields');
    }
};
