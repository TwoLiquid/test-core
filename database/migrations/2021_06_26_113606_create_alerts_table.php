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
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->index();
            $table->bigInteger('type_id')->unsigned()->index();
            $table->bigInteger('animation_id')->unsigned()->index();
            $table->bigInteger('align_id')->unsigned()->index();
            $table->bigInteger('text_font_id')->unsigned()->index();
            $table->bigInteger('text_style_id')->unsigned()->index();
            $table->bigInteger('logo_align_id')->unsigned()->index();
            $table->bigInteger('cover_id')->unsigned()->index();
            $table->integer('duration');
            $table->string('text_font_color');
            $table->integer('text_font_size');
            $table->string('logo_color');
            $table->integer('volume')->default(80);
            $table->string('username')->nullable();
            $table->double('amount')->nullable();
            $table->string('action')->nullable();
            $table->text('message')->nullable();
            $table->text('widget_url')->nullable();
            $table->timestamps();
            $table->softDeletes();

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
        Schema::dropIfExists('alerts');
    }
};
