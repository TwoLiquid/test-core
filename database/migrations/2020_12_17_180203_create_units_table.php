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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('type_id')->unsigned()->index();
            $table->string('code');
            $table->json('name');
            $table->integer('duration')->nullable();
            $table->boolean('visible')->default(true);
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
        Schema::dropIfExists('units');
    }
};
