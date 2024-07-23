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
        Schema::create('tip_invoices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tip_id')->unsigned()->index();
            $table->bigInteger('type_id')->unsigned()->index();
            $table->bigInteger('status_id')->unsigned()->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tip_id')
                ->references('id')
                ->on('tips')
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
        Schema::dropIfExists('tip_invoices');
    }
};
