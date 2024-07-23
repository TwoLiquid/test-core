<?php

namespace Database\Seeders;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

/**
 * Class TimezoneSeeder
 *
 * @package Database\Seeders
 */
class TimezoneSeeder extends Seeder
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
         * Seeding timezone offsets dump
         */
        if (File::exists(base_path() . '/database/dumps/timezone_offsets.sql')) {
            DB::unprepared(File::get(base_path() . '/database/dumps/timezone_offsets.sql'));
        }

        /**
         * Seeding timezones dump
         */
        if (File::exists(base_path() . '/database/dumps/timezones.sql')) {
            DB::unprepared(File::get(base_path() . '/database/dumps/timezones.sql'));
        }

        /**
         * Seeding offset timezone relations dump
         */
        if (File::exists(base_path() . '/database/dumps/offset_timezone.sql')) {
            DB::unprepared(File::get(base_path() . '/database/dumps/offset_timezone.sql'));
        }

        /**
         * Seeding timezone time changes dump
         */
        if (File::exists(base_path() . '/database/dumps/timezone_time_changes.sql')) {
            DB::unprepared(File::get(base_path() . '/database/dumps/timezone_time_changes.sql'));
        }
    }
}
