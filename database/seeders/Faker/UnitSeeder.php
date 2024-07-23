<?php

namespace Database\Seeders\Faker;

use App\Lists\Unit\Type\UnitTypeList;
use App\Models\MySql\Unit;
use Illuminate\Database\Seeder;

/**
 * Class UnitSeeder
 *
 * @package Database\Seeders\Faker
 */
class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() : void
    {
        $units = [
            [
                'name' => [
                    'en' => 'Game',
                    'zh' => '游戏',
                    'es' => 'Juego',
                    'fr' => 'Jeu',
                    'pt' => 'Jogo',
                    'de' => 'Spiel',
                    'ja' => 'ゲーム',
                    'it' => 'Gioco',
                    'uk' => 'Гра'
                ],
                'type_id'  => 1,
                'duration' => 120,
                'visible'  => true
            ],
            [
                'name' => [
                    'en' => 'Match',
                    'zh' => '匹配',
                    'es' => 'Fósforo',
                    'fr' => 'Correspondre',
                    'pt' => 'Corresponder',
                    'de' => 'Übereinstimmen',
                    'ja' => 'マッチ',
                    'it' => 'Incontro',
                    'uk' => 'Матч'
                ],
                'type_id'  => 1,
                'duration' => 90,
                'visible'  => true
            ],
            [
                'name' => [
                    'en' => 'Hour',
                    'zh' => '小时',
                    'es' => 'Hora',
                    'fr' => 'Heure',
                    'pt' => 'Hora',
                    'de' => 'Stunde',
                    'ja' => '時間',
                    'it' => 'Ora',
                    'uk' => 'Годину'
                ],
                'type_id'  => 1,
                'duration' => 60,
                'visible'  => true
            ],
            [
                'name' => [
                    'en' => '30 minutes',
                    'zh' => '30分钟',
                    'es' => '30 minutos',
                    'fr' => '30 minutes',
                    'pt' => '30 minutos',
                    'de' => '30 Minuten',
                    'ja' => '30分',
                    'it' => '30 minuti',
                    'uk' => '30 хвилин'
                ],
                'type_id'  => 1,
                'duration' => 30,
                'visible'  => true
            ],
            [
                'name' => [
                    'en' => 'Ticket',
                    'zh' => '票',
                    'es' => 'Boleto',
                    'fr' => 'Billet',
                    'pt' => 'Bilhete',
                    'de' => 'Fahrkarte',
                    'ja' => 'チケット',
                    'it' => 'Biglietto',
                    'uk' => 'Квиток'
                ],
                'type_id'  => 2,
                'duration' => null,
                'visible'  => true
            ],
            [
                'name' => [
                    'en' => 'Entry',
                    'zh' => '入口',
                    'es' => 'Entrada',
                    'fr' => 'Entrée',
                    'pt' => 'Entrada',
                    'de' => 'Eintrag',
                    'ja' => 'エントリ',
                    'it' => 'Iscrizione',
                    'uk' => 'Вхід'
                ],
                'type_id'  => 2,
                'duration' => null,
                'visible'  => true
            ]
        ];

        foreach ($units as $unitItem) {
            $unit = new Unit();
            $unit->type_id = UnitTypeList::getItem($unitItem['type_id'])->id;
            $unit->name = $unitItem['name'];
            $unit->code = generateCodeByName($unitItem['name']['en']);
            $unit->duration = $unitItem['duration'];
            $unit->visible = $unitItem['visible'];
            $unit->save();
        }
    }
}
