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
        Schema::connection('mongodb')->table('tax_rule_country_history', function (Blueprint $collection) {
            $collection->id();
            $collection->integer('tax_rule_country_id')->unsigned()->index();
            $collection->double('from_tax_rate');
            $collection->timestamp('from_date');
            $collection->double('to_tax_rate')->nullable();
            $collection->timestamp('to_date')->nullable();
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
        Schema::connection('mongodb')->table('tax_rule_country_history', function (Blueprint $collection) {
            $collection->drop();
        });
    }
};

