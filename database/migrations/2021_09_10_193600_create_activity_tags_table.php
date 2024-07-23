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
        Schema::create('activity_tags', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('category_id')->unsigned()->index()->nullable();
            $table->bigInteger('subcategory_id')->unsigned()->index()->nullable();
            $table->string('code');
            $table->json('name');
            $table->boolean('visible_in_category')->default(true);
            $table->boolean('visible_in_subcategory')->default(true);

            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('subcategory_id')
                ->references('id')
                ->on('categories')
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
        Schema::dropIfExists('activity_tags');
    }
};
