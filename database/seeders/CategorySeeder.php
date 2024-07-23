<?php

namespace Database\Seeders;

use App\Models\MySql\Category;
use Illuminate\Database\Seeder;

/**
 * Class CategorySeeder
 *
 * @package Database\Seeders
 */
class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() : void
    {
        $categories = [
            [
                'name' => [
                    'en' => 'Games',
                    'es' => 'Juegos',
                    'ja' => 'ゲーム',
                    'zh' => '遊戲',
                    'fr' => 'Jeux',
                    'pt' => 'Jogos',
                    'de' => 'Spiele',
                    'it' => 'Giochi',
                    'uk' => 'Ігри'
                ],
                'subcategories' => [
                    [
                        'name' => [
                            'en' => 'Tabletop games',
                            'es' => 'Juegos de mesa',
                            'ja' => '卓上ゲーム',
                            'zh' => '桌面遊戲',
                            'fr' => 'Jeux de table',
                            'pt' => 'Jogos de mesa',
                            'de' => 'Tabletop-Spiele',
                            'it' => 'Giochi da tavolo',
                            'uk' => 'Настільні ігри'
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Video games',
                            'es' => 'Juegos de vídeo',
                            'ja' => 'ビデオゲーム',
                            'zh' => '視頻遊戲',
                            'fr' => 'Jeux vidéo',
                            'pt' => 'Jogos de vídeo',
                            'de' => 'Videospiele',
                            'it' => 'Videogiochi',
                            'uk' => 'Відео ігри'
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Party games',
                            'es' => 'Juegos de vídeo',
                            'ja' => 'ビデオゲーム',
                            'zh' => '視頻遊戲',
                            'fr' => 'Jeux vidéo',
                            'pt' => 'Jogos de vídeo',
                            'de' => 'Videospiele',
                            'it' => 'Videogiochi',
                            'uk' => 'Відео ігри'
                        ]
                    ]
                ]
            ],
            [
                'name' => [
                    'en' => 'Hanging out',
                    'es' => 'Pasando el rato',
                    'ja' => 'ぶらぶら',
                    'zh' => '閒逛',
                    'fr' => 'Traîner',
                    'pt' => 'Saindo',
                    'de' => 'Abhängen',
                    'it' => 'Uscire',
                    'uk' => 'Висить'
                ]
            ],
            [
                'name' => [
                    'en' => 'Sports',
                    'es' => 'Deportes',
                    'ja' => 'スポーツ',
                    'zh' => '運動的',
                    'fr' => 'Des sports',
                    'pt' => 'Esportes',
                    'de' => 'Sport',
                    'it' => 'Gli sport',
                    'uk' => 'Спорт'
                ]
            ],
            [
                'name' => [
                    'en' => 'Singing',
                    'es' => 'Cantando',
                    'ja' => '歌う',
                    'zh' => '唱歌',
                    'fr' => 'En chantant',
                    'pt' => 'Cantoria',
                    'de' => 'Singen',
                    'it' => 'Cantando',
                    'uk' => 'Спів'
                ]
            ],
            [
                'name' => [
                    'en' => 'Events',
                    'es' => 'Eventos',
                    'ja' => 'イベント',
                    'zh' => '事件',
                    'fr' => 'Événements',
                    'pt' => 'Eventos',
                    'de' => 'Veranstaltungen',
                    'it' => 'Eventi',
                    'uk' => 'Події'
                ]
            ]
        ];

        /** @var array $categoryItem */
        foreach ($categories as $categoryItem) {
            $category = new Category();
            $category->name = $categoryItem['name'];
            $category->code = generateCodeByName($categoryItem['name']['en']);
            $category->save();

            if (isset($categoryItem['subcategories'])) {

                /** @var array $subcategoryItem */
                foreach ($categoryItem['subcategories'] as $subcategoryItem) {
                    $subcategory = new Category();
                    $subcategory->parent_id = $category->id;
                    $subcategory->name = $subcategoryItem['name'];
                    $subcategory->code = generateCodeByName($subcategoryItem['name']['en']);
                    $subcategory->save();
                }
            }
        }
    }
}
