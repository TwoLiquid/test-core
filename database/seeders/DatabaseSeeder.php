<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Class DatabaseSeeder
 *
 * @package Database\Seeders
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run() : void
    {
        $this->call(AdminSeeder::class);
        $this->call(CountryPlaceSeeder::class);
        $this->call(PhoneCodeSeeder::class);
        $this->call(TimezoneSeeder::class);
        $this->call(CityPlaceSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(DeviceSeeder::class);
        $this->call(PlatformSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(PaymentMethodSeeder::class);
    }
}
