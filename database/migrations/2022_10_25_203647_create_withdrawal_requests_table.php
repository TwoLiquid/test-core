<?php

use MongoDB\Laravel\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
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
        Schema::connection('mongodb')->table('withdrawal_requests', function (Blueprint $collection) {
            $collection->id();
            $collection->integer('user_id')->index();
            $collection->integer('method_id');
            $collection->integer('receipt_id');
            $collection->integer('status_id');
            $collection->double('amount');
            $collection->integer('toast_message_type_id');
            $collection->string('toast_message_text');
            $collection->integer('admin_id')->index();
            $collection->integer('language_id')->index();
            $collection->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() : void
    {
        Schema::connection('mongodb')->table('withdrawal_requests', function (Blueprint $collection) {
            $collection->drop();
        });
    }
};

