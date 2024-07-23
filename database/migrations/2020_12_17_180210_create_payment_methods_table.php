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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('payment_status_id')->unsigned()->index();
            $table->bigInteger('withdrawal_status_id')->unsigned()->index();
            $table->string('name');
            $table->string('code');
            $table->double('payment_fee')->nullable();
            $table->boolean('order_form')->default(false);
            $table->json('display_name')->nullable();
            $table->json('duration_title')->nullable();
            $table->json('duration_amount')->nullable();
            $table->json('fee_title')->nullable();
            $table->json('fee_amount')->nullable();
            $table->boolean('standard')->default(false);
            $table->boolean('integrated')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() : void
    {
        Schema::dropIfExists('payment_methods');
    }
};
