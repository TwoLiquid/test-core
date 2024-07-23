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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('auth_id')->unsigned()->index();
            $table->bigInteger('status_id')->unsigned()->index();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->boolean('full_access')->default(false);
            $table->boolean('initial_password')->default(true);
            $table->string('password_reset_token')->nullable();
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
        Schema::dropIfExists('admins');
    }
};
