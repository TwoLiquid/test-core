<?php

namespace Database\Seeders;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

/**
 * Class PhoneCodeSeeder
 *
 * @package Database\Seeders
 */
class PhoneCodeSeeder extends Seeder
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
        if (File::exists(base_path() . '/database/dumps/phone_codes.sql')) {
            DB::unprepared(File::get(base_path() . '/database/dumps/phone_codes.sql'));
        }
    }
}
