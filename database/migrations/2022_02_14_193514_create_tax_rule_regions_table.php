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
        Schema::create('tax_rule_regions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tax_rule_country_id')->unsigned()->index();
            $table->string('region_place_id')->index();
            $table->double('tax_rate');
            $table->timestamp('from_date');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tax_rule_country_id')
                ->references('id')
                ->on('tax_rule_countries')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('region_place_id')
                ->references('place_id')
                ->on('region_places')
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
        Schema::dropIfExists('tax_rule_regions');
    }
};
