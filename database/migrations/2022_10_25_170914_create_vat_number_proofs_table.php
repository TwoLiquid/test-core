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
        Schema::connection('mongodb')->table('vat_number_proofs', function (Blueprint $collection) {
            $collection->id();
            $collection->integer('billing_id')->index();
            $collection->string('country_place_id');
            $collection->string('company_name');
            $collection->string('vat_number');
            $collection->boolean('exclude_tax');
            $collection->boolean('main');
            $collection->integer('status_id');
            $collection->integer('admin_id');
            $collection->string('action_date');
            $collection->string('exclude_tax_date');
            $collection->string('status_change_date');
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
        Schema::connection('mongodb')->table('vat_number_proofs', function (Blueprint $collection) {
            $collection->drop();
        });
    }
};

