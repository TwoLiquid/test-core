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
        Schema::connection('mongodb')->table('exclude_tax_history', function (Blueprint $collection) {
            $collection->id();
            $collection->integer('vat_number_proof_id')->index();
            $collection->boolean('value');
            $collection->integer('admin_id')->index();
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
        Schema::connection('mongodb')->table('exclude_tax_history', function (Blueprint $collection) {
            $collection->drop();
        });
    }
};
