<?php

namespace App\Http\Controllers;

use App\Models\MySql\User\User;
use App\Repositories\User\UserRepository;
use App\Services\Order\OrderService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * Class MainController
 *
 * @package App\Http\Controllers
 */
class MainController extends Controller
{
    /**
     * @var OrderService
     */
    protected OrderService $orderService;

    /**
     * MainController constructor
     */
    public function __construct()
    {
        /** @var OrderService orderService */
        $this->orderService = new OrderService();
    }

    /**
     * @return View
     */
    public function index() : View
    {
//        $items = [
//            'AF' => '93',
//            'AX' => '358',
//            'AL' => '355',
//            'DZ' => '213',
//            'AS' => '1-684',
//            'AD' => '376',
//            'AO' => '244',
//            'AI' => '1-264',
//            'AG' => '1-268',
//            'AR' => '54',
//            'AM' => '374',
//            'AW' => '297',
//            'AU' => '61',
//            'AT' => '43',
//            'AZ' => '994',
//            'BS' => '1-242',
//            'BH' => '973',
//            'BD' => '880',
//            'BB' => '1-246',
//            'BY' => '375',
//            'BE' => '32',
//            'BZ' => '501',
//            'BJ' => '229',
//            'BM' => '1-441',
//            'BT' => '975',
//            'BO' => '591',
//            'BQ' => '599',
//            'BA' => '387',
//            'BW' => '267',
//            'BR' => '55',
//            'BN' => '673',
//            'BG' => '359',
//            'BF' => '226',
//            'BI' => '257',
//            'CV' => '238',
//            'KH' => '855',
//            'CM' => '237',
//            'CA' => '1',
//            'KY' => '1-345',
//            'CF' => '236',
//            'TD' => '235',
//            'CL' => '56',
//            'CN' => '86',
//            'CX' => '61',
//            'CC' => '61',
//            'CO' => '57',
//            'KM' => '269',
//            'CG' => '242',
//            'CD' => '243',
//            'CK' => '682',
//            'CR' => '506',
//            'CI' => '225',
//            'HR' => '385',
//            'CU' => '53',
//            'CW' => '599',
//            'CY' => '357',
//            'CZ' => '420',
//            'DK' => '45',
//            'DJ' => '253',
//            'DM' => '1-767',
//            'DO' => '1',
//            'EC' => '593',
//            'EG' => '20',
//            'SV' => '503',
//            'GQ' => '240',
//            'ER' => '291',
//            'EE' => '372',
//            'ET' => '251',
//            'FK' => '500',
//            'FO' => '298',
//            'FJ' => '679',
//            'FI' => '358',
//            'FR' => '33',
//            'GF' => '594',
//            'PF' => '689',
//            'GA' => '241',
//            'GM' => '220',
//            'GE' => '995',
//            'DE' => '49',
//            'GH' => '233',
//            'GI' => '350',
//            'GR' => '30',
//            'GL' => '299',
//            'GD' => '1-473',
//            'GP' => '590',
//            'GU' => '1-671',
//            'GT' => '502',
//            'GG' => '44',
//            'GN' => '224',
//            'GW' => '245',
//            'GY' => '592',
//            'HT' => '509',
//            'VA' => '379',
//            'HN' => '504',
//            'HK' => '852',
//            'HU' => '36',
//            'IS' => '354',
//            'IN' => '91',
//            'ID' => '62',
//            'IR' => '98',
//            'IQ' => '964',
//            'IE' => '353',
//            'IM' => '44',
//            'IL' => '972',
//            'IT' => '39',
//            'JM' => '1-876',
//            'JP' => '81',
//            'JE' => '44',
//            'JO' => '962',
//            'KZ' => '7',
//            'KE' => '254',
//            'KI' => '686',
//            'KP' => '850',
//            'KR' => '82',
//            'KW' => '965',
//            'KG' => '996',
//            'LA' => '856',
//            'LV' => '371',
//            'LB' => '961',
//            'LS' => '266',
//            'LR' => '231',
//            'LY' => '218',
//            'LI' => '423',
//            'LT' => '370',
//            'LU' => '352',
//            'MO' => '853',
//            'MK' => '389',
//            'MG' => '261',
//            'MW' => '265',
//            'MY' => '60',
//            'MV' => '960',
//            'ML' => '223',
//            'MT' => '356',
//            'MH' => '692',
//            'MQ' => '596',
//            'MR' => '222',
//            'MU' => '230',
//            'YT' => '262',
//            'MX' => '52',
//            'FM' => '691',
//            'MD' => '373',
//            'MC' => '377',
//            'MN' => '976',
//            'ME' => '382',
//            'MS' => '1-664',
//            'MA' => '212',
//            'MZ' => '258',
//            'MM' => '95',
//            'NA' => '264',
//            'NR' => '674',
//            'NP' => '977',
//            'NL' => '31',
//            'NC' => '687',
//            'NZ' => '64',
//            'NI' => '505',
//            'NE' => '227',
//            'NG' => '234',
//            'NU' => '683',
//            'NF' => '672',
//            'MP' => '1-670',
//            'NO' => '47',
//            'OM' => '968',
//            'PK' => '92',
//            'PW' => '680',
//            'PS' => '970',
//            'PA' => '507',
//            'PG' => '675',
//            'PY' => '595',
//            'PE' => '51',
//            'PH' => '63',
//            'PN' => '64',
//            'PL' => '48',
//            'PT' => '351',
//            'PR' => '1',
//            'QA' => '974',
//            'RE' => '262',
//            'RO' => '40',
//            'RU' => '7',
//            'RW' => '250',
//            'BL' => '590',
//            'SH' => '290',
//            'KN' => '1-869',
//            'LC' => '1-758',
//            'MF' => '590',
//            'VC' => '1-784',
//            'WS' => '685',
//            'SM' => '378',
//            'ST' => '239',
//            'SA' => '966',
//            'SN' => '221',
//            'RS' => '381',
//            'SC' => '248',
//            'SL' => '232',
//            'SG' => '65',
//            'SX' => '1-721',
//            'SK' => '421',
//            'SI' => '386',
//            'SB' => '677',
//            'SO' => '252',
//            'ZA' => '27',
//            'GS' => '500',
//            'SS' => '211',
//            'ES' => '34',
//            'LK' => '94',
//            'SD' => '249',
//            'SR' => '597',
//            'SJ' => '47',
//            'SZ' => '268',
//            'SE' => '46',
//            'CH' => '41',
//            'SY' => '963',
//            'TW' => '886',
//            'TJ' => '992',
//            'TZ' => '242',
//            'TH' => '66',
//            'TL' => '670',
//            'TG' => '228',
//            'TK' => '690',
//            'TO' => '676',
//            'TT' => '1-868',
//            'TN' => '216',
//            'TR' => '90',
//            'TM' => '993',
//            'TC' => '1-649',
//            'TV' => '688',
//            'UG' => '256',
//            'UA' => '380',
//            'AE' => '971',
//            'GB' => '44',
//            'US' => '1',
//            'UY' => '598',
//            'UZ' => '998',
//            'VU' => '678',
//            'VE' => '58',
//            'VN' => '84',
//            'VG' => '1-284',
//            'VI' => '1-340',
//            'WF' => '681',
//            'YE' => '967',
//            'ZM' => '260',
//            'ZW' => '263'
//        ];
//
//        /**
//         * @var string $key
//         * @var string $item
//         */
//        foreach ($items as $key => $item) {
//            $phoneCode = PhoneCode::query()
//                ->where('code', '=', $item)
//                ->first();
//
//            if (!$phoneCode) {
//                $phoneCode = PhoneCode::create([
//                    'code' => trim($item)
//                ]);
//            }
//
//            $countryPlace = CountryPlace::query()
//                ->where('code', '=', trim($key))
//                ->first();
//
//            $countryPlace->update([
//                'phone_code_id' => $phoneCode->id
//            ]);
//        }

//        $template = [
//            'en' => '',
//            'zn' => '',
//            'es' => '',
//            'fr' => '',
//            'pt' => '',
//            'de' => '',
//            'ja' => '',
//            'uk' => '',
//            'it' => ''
//        ];
//
//        $bahamas = CountryPlace::find(16);
//        $bahamas->setTranslations('name', [
//            'en' => 'The Bahamas',
//            'zn' => '巴哈馬',
//            'es' => 'Bahamas',
//            'fr' => 'Les Bahamas',
//            'pt' => 'Bahamas',
//            'de' => 'Bahamas',
//            'ja' => 'バハマ',
//            'uk' => 'Багамські Острови',
//            'it' => 'Bahamas'
//        ])->save();
//
//        $caboVerde = CountryPlace::find(35);
//        $caboVerde->setTranslations('name', [
//            'en' => 'Cabo Verde',
//            'zn' => '佛得角',
//            'es' => 'Cabo Verde',
//            'fr' => 'Le Cap-Vert',
//            'pt' => 'Cabo Verde',
//            'de' => 'Kap Verde',
//            'ja' => 'カーボベルデ',
//            'uk' => 'Кабо-Верде',
//            'it' => 'Capo Verde'
//        ])->save();
//
//        $republicOfTheCongo = CountryPlace::find(48);
//        $republicOfTheCongo->setTranslations('name', [
//            'en' => 'Republic of the Congo',
//            'zn' => '剛果共和國',
//            'es' => 'República del Congo',
//            'fr' => 'République du Congo',
//            'pt' => 'República do Congo',
//            'de' => 'Republik Kongo',
//            'ja' => 'コンゴ共和国',
//            'uk' => 'Республіка Конго',
//            'it' => 'Repubblica del Congo'
//        ])->save();
//
//        $ivoryCoast = CountryPlace::find(52);
//        $ivoryCoast->setTranslations('name', [
//            'en' => 'Côte d\'Ivoire',
//            'zn' => '科特迪瓦',
//            'es' => 'Costa de Marfil',
//            'fr' => 'Côte d\'Ivoire',
//            'pt' => 'Costa do Marfim',
//            'de' => 'Elfenbeinküste',
//            'ja' => 'コートジボワール',
//            'uk' => 'Кот-д\'Івуар',
//            'it' => 'Costa d\'Avorio'
//        ])->save();
//
//        $falklandIslands = CountryPlace::find(69);
//        $falklandIslands->setTranslations('name', [
//            'en' => 'Falkland Islands (Islas Malvinas)',
//            'zn' => '福克蘭群島',
//            'es' => 'Islas Malvinas',
//            'fr' => 'Îles Malouines',
//            'pt' => 'Ilhas Malvinas',
//            'de' => 'Falklandinseln (Malwinen)',
//            'ja' => 'フォークランド諸島',
//            'uk' => 'Фолклендські (Мальвінські) острови',
//            'it' => 'Isole Falkland (Malvine)'
//        ])->save();
//
//        $frenchSouthernTerritories = CountryPlace::find(74);
//        $frenchSouthernTerritories->setTranslations('name', [
//            'en' => 'French Southern and Antarctic Lands',
//            'zn' => '法屬南部和南極領地',
//            'es' => 'Tierras Australes y Antárticas Francesas',
//            'fr' => 'Terres australes et antarctiques françaises',
//            'pt' => 'Terras Austrais e Antárticas Francesas',
//            'de' => 'Französische Süd- und Antarktisgebiete',
//            'ja' => 'フランス領南方・南極地域',
//            'uk' => 'Французькі Південні і Антарктичні Території',
//            'it' => 'Terre Australi e Antartiche Francesi'
//        ])->save();
//
//        $gambia = CountryPlace::find(77);
//        $gambia->setTranslations('name', [
//            'en' => 'The Gambia',
//            'zn' => '岡比亞',
//            'es' => 'Gambia',
//            'fr' => 'Gambie',
//            'pt' => 'Gâmbia',
//            'de' => 'Gambia',
//            'ja' => 'ガンビア',
//            'uk' => 'Гамбія',
//            'it' => 'Gambia'
//        ])->save();
//
//        $vatican = CountryPlace::find(93);
//        $vatican->setTranslations('name', [
//            'en' => 'Vatican City State',
//            'zn' => '梵蒂岡',
//            'es' => 'Ciudad del Vaticano',
//            'fr' => 'Vatican',
//            'pt' => 'Cidade do Vaticano',
//            'de' => 'Staat Vatikanstadt',
//            'ja' => 'バチカン',
//            'uk' => 'Ватикан',
//            'it' => 'Città del Vaticano'
//        ])->save();
//
//        $northMacedonia = CountryPlace::find(127);
//        $northMacedonia->setTranslations('name', [
//            'en' => 'North Macedonia',
//            'zn' => '北馬其頓',
//            'es' => 'Macedonia del Norte',
//            'fr' => 'Macédoine du Nord',
//            'pt' => 'Macedónia do Norte',
//            'de' => 'Nordmazedonien',
//            'ja' => '北マケドニア',
//            'uk' => 'Північна Македонія',
//            'it' => 'Macedonia del Nord'
//        ])->save();
//
//        $micronesia = CountryPlace::find(140);
//        $micronesia->setTranslations('name', [
//            'en' => 'Federated States of Micronesia',
//            'zn' => '密克羅尼西亞聯邦',
//            'es' => 'Estados Federados de Micronesia',
//            'fr' => 'États fédérés de Micronésie',
//            'pt' => 'Estados Federados da Micronésia',
//            'de' => 'Föderierte Staaten von Mikronesien',
//            'ja' => 'ミクロネシア連邦',
//            'uk' => 'Федеративні Штати Мікронезії',
//            'it' => 'Stati Federati di Micronesia'
//        ])->save();
//
//        $moldova = CountryPlace::find(141);
//        $moldova->setTranslations('name', [
//            'en' => 'Moldova',
//            'zn' => '摩爾多瓦',
//            'es' => 'Moldavia',
//            'fr' => 'Moldavie',
//            'pt' => 'Moldávia',
//            'de' => 'Republik Moldau',
//            'ja' => 'モルドバ',
//            'uk' => 'Молдова',
//            'it' => 'Moldavia'
//        ])->save();
//
//        $netherlands = CountryPlace::find(152);
//        $netherlands->setTranslations('name', [
//            'en' => 'The Netherlands',
//            'zn' => '荷蘭',
//            'es' => 'Países Bajos',
//            'fr' => 'Pays-Bas',
//            'pt' => 'Países Baixos',
//            'de' => 'Niederlande',
//            'ja' => 'オランダ',
//            'uk' => 'Нідерланди',
//            'it' => 'Paesi Bassi'
//        ])->save();
//
//        $palestine = CountryPlace::find(165);
//        $palestine->setTranslations('name', [
//            'en' => 'State of Palestine',
//            'zn' => '巴勒斯坦國',
//            'es' => 'Estado de Palestina',
//            'fr' => 'État de Palestine',
//            'pt' => 'Estado da Palestina',
//            'de' => 'Staat Palästina',
//            'ja' => 'パレスチナ国',
//            'uk' => 'Держава Палестина',
//            'it' => 'Stato di Palestina'
//        ])->save();
//
//        $philippines = CountryPlace::find(170);
//        $philippines->setTranslations('name', [
//            'en' => 'The Philippines',
//            'zn' => '菲律賓',
//            'es' => 'Filipinas',
//            'fr' => 'Filipinas',
//            'pt' => 'Philippines',
//            'de' => 'Philippinen',
//            'ja' => 'フィリピン',
//            'uk' => 'Філіппіни',
//            'it' => 'Filippine'
//        ])->save();
//
//        $saintMartin = CountryPlace::find(184);
//        $saintMartin->setTranslations('name', [
//            'en' => 'Collectivity of Saint Martin',
//            'zn' => '法屬聖馬丁',
//            'es' => 'San Martín (Francia)',
//            'fr' => 'Saint-Martin (Antilles françaises)',
//            'pt' => 'Coletividade de São Martinho',
//            'de' => 'Saint-Martin',
//            'ja' => 'サン・マルタン',
//            'uk' => 'Сен-Мартен',
//            'it' => 'Saint-Martin'
//        ])->save();
//
//        $saoTomeAndPrincipe = CountryPlace::find(188);
//        $saoTomeAndPrincipe->setTranslations('name', [
//            'en' => 'São Tomé and Príncipe',
//            'zn' => '聖多美和普林西比',
//            'es' => 'Santo Tomé y Príncipe',
//            'fr' => 'Sao Tomé-et-Principe',
//            'pt' => 'São Tomé e Príncipe',
//            'de' => 'São Tomé und Príncipe',
//            'ja' => 'サントメ・プリンシペ',
//            'uk' => 'Сан-Томе і Принсіпі',
//            'it' => 'São Tomé e Príncipe'
//        ])->save();
//
//        $sintMaarten = CountryPlace::find(195);
//        $sintMaarten->setTranslations('name', [
//            'en' => 'Sint Maarten',
//            'zn' => '荷屬聖馬丁',
//            'es' => 'San Martín (Países Bajos)',
//            'fr' => 'Saint-Martin (Pays-Bas)',
//            'pt' => 'São Martinho (Países Baixos)',
//            'de' => 'Sint Maarten',
//            'ja' => 'シント・マールテン',
//            'uk' => 'Сінт-Мартен',
//            'it' => 'Sint Maarten'
//        ])->save();
//
//        $eswatini = CountryPlace::find(208);
//        $eswatini->setTranslations('name', [
//            'en' => 'Eswatini',
//            'zn' => '史瓦帝尼',
//            'es' => 'Esuatini',
//            'fr' => 'Eswatini',
//            'pt' => 'Essuatíni',
//            'de' => 'Eswatini',
//            'ja' => 'エスワティニ',
//            'uk' => 'Есватіні',
//            'it' => 'eSwatini'
//        ])->save();
//
//        $taiwan = CountryPlace::find(212);
//        $taiwan->setTranslations('name', [
//            'en' => 'Republic of China (Taiwan)',
//            'zn' => '中華民國（台灣）',
//            'es' => 'República de China (Taiwan)',
//            'fr' => 'République de Chine (Taïwan)',
//            'pt' => 'República da China (Taiwan)',
//            'de' => 'Republik China (Taiwan)',
//            'ja' => '中華民国（台湾）',
//            'uk' => 'Республіка Китай (Тайвань)',
//            'it' => 'Repubblica di Cina (Taiwan)'
//        ])->save();
//
//        $turkiye = CountryPlace::find(222);
//        $turkiye->setTranslations('name', [
//            'en' => 'Türkiye',
//            'zn' => '土耳其',
//            'es' => 'Turquía',
//            'fr' => 'Turquie',
//            'pt' => 'Turquia',
//            'de' => 'Türkei',
//            'ja' => 'トルコ',
//            'uk' => 'Туреччина',
//            'it' => 'Turchia'
//        ])->save();
//
//        $virginIslands = CountryPlace::find(236);
//        $virginIslands->setTranslations('name', [
//            'en' => 'Virgin Islands',
//            'zn' => '英屬維爾京群島',
//            'es' => 'Islas Vírgenes Británicas',
//            'fr' => 'Îles Vierges britanniques',
//            'pt' => 'Ilhas Virgens Britânicas',
//            'de' => 'Britische Jungferninseln',
//            'ja' => 'イギリス領ヴァージン諸島',
//            'uk' => 'Британські Віргінські Острови',
//            'it' => 'Isole Vergini Britanniche'
//        ])->save();
//
//        $virginIslandsOfTheUnitedStates = CountryPlace::find(237);
//        $virginIslandsOfTheUnitedStates->setTranslations('name', [
//            'en' => 'Virgin Islands of the United States',
//            'zn' => '美屬維爾京群島',
//            'es' => 'Islas Vírgenes de los Estados Unidos',
//            'fr' => 'Îles Vierges des États-Unis',
//            'pt' => 'Ilhas Virgens Americanas',
//            'de' => 'Amerikanische Jungferninseln',
//            'ja' => 'アメリカ領ヴァージン諸島',
//            'uk' => 'Американські Віргінські Острови',
//            'it' => 'Isole Vergini Americane'
//        ])->save();

        return view('welcome');
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function testPayment(
        Request $request
    ) : array
    {
        return $request->all();
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function testPaymentCancel(
        Request $request
    ) : array
    {
        return $request->all();
    }
}
