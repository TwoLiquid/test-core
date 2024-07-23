<?php

namespace Database\Seeders;

use App\Models\MySql\Platform;
use Illuminate\Database\Seeder;

/**
 * Class PlatformSeeder
 *
 * @package Database\Seeders
 */
class PlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() : void
    {
        $platforms = [
            [
                'name'                  => 'Discord',
                'voice_chat'            => true,
                'visible_in_voice_chat' => true,
                'video_chat'            => true,
                'visible_in_video_chat' => true
            ],
            [
                'name'                  => 'Houseparty',
                'voice_chat'            => true,
                'visible_in_voice_chat' => true,
                'video_chat'            => true,
                'visible_in_video_chat' => true
            ],
            [
                'name'                  => 'Zoom',
                'voice_chat'            => true,
                'visible_in_voice_chat' => true,
                'video_chat'            => true,
                'visible_in_video_chat' => true
            ],
            [
                'name'                  => 'Skype',
                'voice_chat'            => true,
                'visible_in_voice_chat' => true,
                'video_chat'            => true,
                'visible_in_video_chat' => true
            ],
            [
                'name'                  => 'Instagram Live',
                'voice_chat'            => true,
                'visible_in_voice_chat' => true,
                'video_chat'            => true,
                'visible_in_video_chat' => true
            ],
            [
                'name'                  => 'WeChat',
                'voice_chat'            => true,
                'visible_in_voice_chat' => true,
                'video_chat'            => true,
                'visible_in_video_chat' => true
            ],
            [
                'name'                  => 'Telegram',
                'voice_chat'            => true,
                'visible_in_voice_chat' => true,
                'video_chat'            => true,
                'visible_in_video_chat' => true
            ],
            [
                'name'                  => 'Viber',
                'voice_chat'            => true,
                'visible_in_voice_chat' => true,
                'video_chat'            => true,
                'visible_in_video_chat' => true
            ],
            [
                'name'                  => 'Tox.chat',
                'voice_chat'            => true,
                'visible_in_voice_chat' => true,
                'video_chat'            => true,
                'visible_in_video_chat' => true
            ],
            [
                'name'                  => 'Facetime',
                'voice_chat'            => true,
                'visible_in_voice_chat' => true,
                'video_chat'            => true,
                'visible_in_video_chat' => true
            ],
            [
                'name'                  => 'Facebook Messenger',
                'voice_chat'            => true,
                'visible_in_voice_chat' => true,
                'video_chat'            => true,
                'visible_in_video_chat' => true
            ]
        ];

        foreach ($platforms as $platformItem) {
            $platform = new Platform();
            $platform->name = $platformItem['name'];
            $platform->code = generateCodeByName($platformItem['name']);
            $platform->voice_chat = $platformItem['voice_chat'];
            $platform->visible_in_voice_chat = $platformItem['visible_in_voice_chat'];
            $platform->video_chat = $platformItem['video_chat'];
            $platform->visible_in_video_chat = $platformItem['visible_in_video_chat'];
            $platform->save();
        }
    }
}
