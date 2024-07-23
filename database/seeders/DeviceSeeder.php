<?php

namespace Database\Seeders;

use App\Models\MySql\Device;
use Illuminate\Database\Seeder;

/**
 * Class DeviceSeeder
 *
 * @package Database\Seeders
 */
class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() : void
    {
        $devices = [
            [
                'name' => 'PC',
                'icon' => 'pc'
            ],
            [
                'name' => 'Playstation 4',
                'icon' => 'ps4'
            ],
            [
                'name' => 'Playstation 5',
                'icon' => 'ps5'
            ],
            [
                'name' => 'Xbox One',
                'icon' => 'xbox_one'
            ],
            [
                'name' => 'iOS',
                'icon' => 'ios'
            ],
            [
                'name' => 'Android',
                'icon' => 'android',
            ],
            [
                'name' => 'Xbox Series X',
                'icon' => 'xbox_x'
            ],
        ];

        foreach ($devices as $deviceItem) {
            $device = new Device();
            $device->name = $deviceItem['name'];
            $device->code = generateCodeByName($deviceItem['name']);
            $device->save();
        }
    }
}
