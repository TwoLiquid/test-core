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
        Schema::create('payment_method_field_user', function (Blueprint $table) {
            $table->bigInteger('field_id')->unsigned()->index();
            $table->bigInteger('user_id')->unsigned()->index();
            $table->json('value');

            $table->foreign('field_id')
                ->references('id')
                ->on('payment_method_fields')
                ->onUpdate('cascade')
                ->onDelete('cascade');

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
        Schema::dropIfExists('payment_method_field_user');
    }
};
