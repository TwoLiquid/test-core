<?php

namespace Database\Seeders\Faker;

use App\Exceptions\DatabaseException;
use App\Exceptions\MicroserviceException;
use App\Lists\Unit\Type\UnitTypeList;
use App\Microservices\Media\MediaMicroservice;
use App\Models\MySql\Activity\Activity;
use App\Models\MySql\Category;
use App\Models\MySql\Device;
use App\Models\MySql\Platform;
use App\Models\MySql\Unit;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Seeder;
use Intervention\Image\Facades\Image;

/**
 * Class ActivitySeeder
 *
 * @package Database\Seeders\Faker
 */
class ActivitySeeder extends Seeder
{
    /**
     * @var MediaMicroservice
     */
    protected MediaMicroservice $mediaMicroservice;

    /**
     * ActivitySeeder constructor
     *
     * @param string|null $token
     *
     * @throws DatabaseException
     */
    public function __construct(
        ?string $token = null
    )
    {
        /** @var MediaMicroservice mediaMicroservice */
        $this->mediaMicroservice = new MediaMicroservice($token);
    }

    /**
     * Run the database seeds
     *
     * @return void
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function run() : void
    {
        $activities = [
            [
                'name' => [
                    'en' => 'Disco Elysium',
                    'es' => 'Disco Elysium',
                    'ja' => 'ディスコ エリジウム',
                    'zh' => '極樂迪斯科',
                    'fr' => 'Disco Elysium',
                    'pt' => 'Disco Elysium',
                    'de' => 'Disco Elysium',
                    'it' => 'Disco Elysium',
                    'uk' => 'Disco Elysium'
                ],
                'visible' => true
            ],
            [
                'name' => [
                    'en' => 'Divinity: Original Sin 2',
                    'es' => 'Divinity: Original Sin 2',
                    'ja' => '神性：原罪2',
                    'zh' => '神界：原罪 2',
                    'fr' => 'Divinity: Original Sin 2',
                    'pt' => 'Divinity: Original Sin 2',
                    'de' => 'Divinity: Original Sin 2',
                    'it' => 'Divinity: Original Sin 2',
                    'uk' => 'Divinity: Original Sin 2'
                ],
                'visible' => true
            ],
            [
                'name' => [
                    'en' => 'Red Dead Redemption 2',
                    'es' => 'Red Dead Redemption 2',
                    'ja' => 'レッド・デッド・リデンプション 2',
                    'zh' => '荒野大鏢客：救贖 2',
                    'fr' => 'Red Dead Redemption 2',
                    'pt' => 'Red Dead Redemption 2',
                    'de' => 'Red Dead Redemption 2',
                    'it' => 'Red Dead Redemption 2',
                    'uk' => 'Red Dead Redemption 2'
                ],
                'visible' => true
            ],
            [
                'name' => [
                    'en' => 'Need for Speed: Porsche Unleashed',
                    'es' => 'Need for Speed: Porsche Unleashed',
                    'ja' => 'ニード・フォー・スピード: ポルシェ・アンリーシュド',
                    'zh' => '極品飛車：保時捷釋放',
                    'fr' => 'Need for Speed: Porsche Unleashed',
                    'pt' => 'Need for Speed: Porsche Unleashed',
                    'de' => 'Need for Speed: Porsche Unleashed',
                    'it' => 'Need for Speed: Porsche Unleashed',
                    'uk' => 'Need for Speed: Porsche Unleashed'
                ],
                'visible' => true
            ],
            [
                'name' => [
                    'en' => 'Castlevania: Lords of Shadow',
                    'es' => 'Castlevania: Lords of Shadow',
                    'ja' => 'キャッスルヴァニア: ロード オブ シャドウ',
                    'zh' => '惡魔城：暗影之王',
                    'fr' => 'Castlevania: Lords of Shadow',
                    'pt' => 'Castlevania: Lords of Shadow',
                    'de' => 'Castlevania: Lords of Shadow',
                    'it' => 'Castlevania: Lords of Shadow',
                    'uk' => 'Castlevania: Lords of Shadow'
                ],
                'visible' => true
            ],
            [
                'name' => [
                    'en' => 'Medal of Honor: Warfighter',
                    'es' => '戰士榮譽勳章',
                    'ja' => 'メダルオブオナーウォーファイター',
                    'zh' => 'Medal of Honor: Warfighter',
                    'fr' => 'Medal of Honor: Warfighter',
                    'pt' => 'Medal of Honor: Warfighter',
                    'de' => 'Medal of Honor: Warfighter',
                    'it' => 'Medal of Honor: Warfighter',
                    'uk' => 'Medal of Honor: Warfighter'
                ],
                'visible' => true
            ],
            [
                'name' => [
                    'en' => 'Grand Theft Auto V',
                    'es' => 'Grand Theft Auto V',
                    'ja' => 'グランド・セフト・オートV',
                    'zh' => '俠盜獵車手V',
                    'fr' => 'Grand Theft Auto V',
                    'pt' => 'Grand Theft Auto V',
                    'de' => 'Grand Theft Auto V',
                    'it' => 'Grand Theft Auto V',
                    'uk' => 'Grand Theft Auto V'
                ],
                'visible' => true
            ],
            [
                'name' => [
                    'en' => 'Max Payne',
                    'es' => 'Max Payne',
                    'ja' => 'マックス・ペイン',
                    'zh' => '馬克思佩恩',
                    'fr' => 'Max Payne',
                    'pt' => 'Max Payne',
                    'de' => 'Max Payne',
                    'it' => 'Max Payne',
                    'uk' => 'Max Payne'
                ],
                'visible' => true
            ],
            [
                'name' => [
                    'en' => 'StarCraft',
                    'es' => 'StarCraft',
                    'ja' => 'スタークラフト',
                    'zh' => '星際爭霸',
                    'fr' => 'StarCraft',
                    'pt' => 'StarCraft',
                    'de' => 'StarCraft',
                    'it' => 'StarCraft',
                    'uk' => 'StarCraft'
                ],
                'visible' => true
            ],
            [
                'name' => [
                    'en' => 'Wolfenstein: The Old Blood',
                    'es' => 'Wolfenstein: The Old Blood',
                    'ja' => 'ウルフェンシュタイン: オールド ブラッド',
                    'zh' => '德軍總部：舊血',
                    'fr' => 'Wolfenstein: The Old Blood',
                    'pt' => 'Wolfenstein: The Old Blood',
                    'de' => 'Wolfenstein: The Old Blood',
                    'it' => 'Wolfenstein: The Old Blood',
                    'uk' => 'Wolfenstein: The Old Blood'
                ],
                'visible' => true
            ],
            [
                'name' => [
                    'en' => 'Age of Empires II: The Age of Kings',
                    'es' => 'Age of Empires II: The Age of Kings',
                    'ja' => 'エイジ オブ エンパイア II: エイジ オブ キングス',
                    'zh' => '帝國時代 II：國王時代',
                    'fr' => 'Age of Empires II: The Age of Kings',
                    'pt' => 'Age of Empires II: The Age of Kings',
                    'de' => 'Age of Empires II: The Age of Kings',
                    'it' => 'Age of Empires II: The Age of Kings',
                    'uk' => 'Age of Empires II: The Age of Kings'
                ],
                'visible' => true
            ],
            [
                'name' => [
                    'en' => 'Ori and the Blind forest',
                    'es' => 'Ori and the Blind forest',
                    'ja' => 'オリと盲目の森',
                    'zh' => '奧日與盲人森林',
                    'fr' => 'Ori and the Blind forest',
                    'pt' => 'Ori and the Blind forest',
                    'de' => 'Ori and the Blind forest',
                    'it' => 'Ori and the Blind forest',
                    'uk' => 'Ori and the Blind forest'
                ],
                'visible' => true
            ],
            [
                'name' => [
                    'en' => 'Gears of War 2',
                    'es' => 'Gears of War 2',
                    'ja' => 'ギアーズ オブ ウォー 2',
                    'zh' => '戰爭機器 2',
                    'fr' => 'Gears of War 2',
                    'pt' => 'Gears of War 2',
                    'de' => 'Gears of War 2',
                    'it' => 'Gears of War 2',
                    'uk' => 'Gears of War 2'
                ],
                'visible' => true
            ],
            [
                'name' => [
                    'en' => 'Horizon: Zero Dawn: The Frozen Wilds',
                    'es' => 'Horizon: Zero Dawn: The Frozen Wilds',
                    'ja' => 'Horizon: Zero Dawn: フローズン ワイルド',
                    'zh' => '地平線：零之黎明：冰凍荒野',
                    'fr' => 'Horizon: Zero Dawn: The Frozen Wilds',
                    'pt' => 'Horizon: Zero Dawn: The Frozen Wilds',
                    'de' => 'Horizon: Zero Dawn: The Frozen Wilds',
                    'it' => 'Horizon: Zero Dawn: The Frozen Wilds',
                    'uk' => 'Horizon: Zero Dawn: The Frozen Wilds'
                ],
                'visible' => true
            ],
            [
                'name' => [
                    'en' => 'Mass Effect 2',
                    'es' => 'Mass Effect 2',
                    'ja' => 'マスエフェクト2',
                    'zh' => '質量效應 2',
                    'fr' => 'Mass Effect 2',
                    'pt' => 'Mass Effect 2',
                    'de' => 'Mass Effect 2',
                    'it' => 'Mass Effect 2',
                    'uk' => 'Mass Effect 2'
                ],
                'visible' => true
            ],
            [
                'name' => [
                    'en' => 'Half-Life 2',
                    'es' => 'Half-Life 2',
                    'ja' => 'ハーフライフ 2',
                    'zh' => '半條命2',
                    'fr' => 'Half-Life 2',
                    'pt' => 'Half-Life 2',
                    'de' => 'Half-Life 2',
                    'it' => 'Half-Life 2',
                    'uk' => 'Half-Life 2'
                ],
                'visible' => true
            ],
            [
                'name' => [
                    'en' => 'The Elder Scrolls V: Skyrim',
                    'es' => 'The Elder Scrolls V: Skyrim',
                    'ja' => 'エルダー スクロールズ V: スカイリム',
                    'zh' => '上古捲軸5：天際',
                    'fr' => 'The Elder Scrolls V: Skyrim',
                    'pt' => 'The Elder Scrolls V: Skyrim',
                    'de' => 'The Elder Scrolls V: Skyrim',
                    'it' => 'The Elder Scrolls V: Skyrim',
                    'uk' => 'The Elder Scrolls V: Skyrim'
                ],
                'visible' => true
            ],
            [
                'name' => [
                    'en' => 'BioShock Infinite',
                    'es' => 'BioShock Infinite',
                    'ja' => 'バイオショック・インフィニット',
                    'zh' => '生化奇兵無限',
                    'fr' => 'BioShock Infinite',
                    'pt' => 'BioShock Infinite',
                    'de' => 'BioShock Infinite',
                    'it' => 'BioShock Infinite',
                    'uk' => 'BioShock Infinite'
                ],
                'visible' => true
            ],
            [
                'name' => [
                    'en' => 'Mortal Kombat',
                    'es' => 'Mortal Kombat',
                    'ja' => 'モータルコンバット',
                    'zh' => '真人快打',
                    'fr' => 'Mortal Kombat',
                    'pt' => 'Mortal Kombat',
                    'de' => 'Mortal Kombat',
                    'it' => 'Mortal Kombat',
                    'uk' => 'Mortal Kombat'
                ],
                'visible' => true
            ],
            [
                'name' => [
                    'en' => 'Alien: Isolation',
                    'es' => 'Alien: Isolation',
                    'ja' => 'エイリアン: アイソレーション',
                    'zh' => '外星人：隔離',
                    'fr' => 'Alien: Isolation',
                    'pt' => 'Alien: Isolation',
                    'de' => 'Alien: Isolation',
                    'it' => 'Alien: Isolation',
                    'uk' => 'Alien: Isolation'
                ],
                'visible' => true
            ]
        ];

        $units = Unit::query()
            ->where('type_id', '=', UnitTypeList::getUsual()->id)
            ->get();

        foreach ($activities as $activityItem) {

            /** @var Category $category */
            $category = Category::inRandomOrder()
                ->first();

            if ($category) {
                $activity = new Activity();
                $activity->category_id = $category->id;
                $activity->name = $activityItem['name'];
                $activity->code = generateCodeByName($activityItem['name']['en']);
                $activity->visible = $activityItem['visible'];
                $activity->save();

                $activity->units()->sync(
                    $units->pluck('id')->toArray()
                );

                if ($category->code == 'video-games') {
                    $devices = Device::inRandomOrder()
                        ->limit(rand(1, Device::count()))
                        ->get();

                    $activity->devices()->sync(
                        $devices->pluck('id')
                            ->values()
                            ->toArray()
                    );
                }

                $platforms = Platform::inRandomOrder()
                    ->limit(rand(1, Platform::count()))
                    ->get();

                $activity->platforms()->sync(
                    $platforms->pluck('id')
                        ->values()
                        ->toArray()
                );

                $posterFileContents = file_get_contents(
                    resource_path('faker/activity/image_poster.jpeg')
                );

                $posterInterventionImage = Image::make(
                    $posterFileContents
                );

                $this->mediaMicroservice->storeActivityImages(
                    $activity, [
                        [
                            'content'   => base64_encode($posterFileContents),
                            'mime'      => $posterInterventionImage->mime(),
                            'extension' => getImageExtensionFromMimeType($posterInterventionImage->mime()),
                            'type'      => 'poster'
                        ]
                    ]
                );

                $backgroundFileContents = file_get_contents(
                    resource_path('faker/activity/image_background.jpeg')
                );

                $backgroundInterventionImage = Image::make(
                    $backgroundFileContents
                );

                $this->mediaMicroservice->storeActivityImages(
                    $activity, [
                        [
                            'content'   => base64_encode($backgroundFileContents),
                            'mime'      => $backgroundInterventionImage->mime(),
                            'extension' => getImageExtensionFromMimeType($backgroundInterventionImage->mime()),
                            'type'      => 'background'
                        ]
                    ]
                );

                $avatarFileContents = file_get_contents(
                    resource_path('faker/activity/image_avatar.jpeg')
                );

                $avatarInterventionImage = Image::make(
                    $avatarFileContents
                );

                $this->mediaMicroservice->storeActivityImages(
                    $activity, [
                        [
                            'content'   => base64_encode($avatarFileContents),
                            'mime'      => $avatarInterventionImage->mime(),
                            'extension' => getImageExtensionFromMimeType($avatarInterventionImage->mime()),
                            'type'      => 'avatar'
                        ]
                    ]
                );
            }
        }
    }
}
