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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('role_id')->unsigned()->index();
            $table->bigInteger('permission_id')->unsigned()->index();
            $table->string('department_code')->index();
            $table->string('page_code')->index();
            $table->boolean('selected')->default(false);

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
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
        Schema::dropIfExists('permissions');
    }
};

