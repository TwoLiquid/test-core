<?php

namespace Database\Seeders;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

/**
 * Class CityPlaceSeeder
 *
 * @package Database\Seeders
 */
class CityPlaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     *
     * @throws FileNotFoundException
     */
    public function run() : void
    {
        /**
         * Seeding city places dump
         */
        if (File::exists(base_path() . '/database/dumps/city_places.sql')) {
            DB::unprepared(File::get(base_path() . '/database/dumps/city_places.sql'));
        }
    }
}
