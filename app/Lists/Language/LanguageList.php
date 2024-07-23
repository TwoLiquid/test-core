<?php

namespace App\Lists\Language;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class LanguageList
 *
 * @package App\Lists\Language
 */
class LanguageList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'language/language';

    /**
     * Languages list constant
     */
    protected const ITEMS = [
        [
            'id'           => 1,
            'code'         => 'english',
            'locale'       => 'en_US',
            'iso'          => 'en',
            'flag'         => 'gb-eng',
            'translatable' => true
        ],
        [
            'id'           => 2,
            'code'         => 'mandarin',
            'locale'       => 'zh_CN',
            'iso'          => 'zh',
            'flag'         => 'cn',
            'translatable' => true
        ],
        [
            'id'           => 3,
            'code'         => 'arabic',
            'locale'       => 'ar_AA',
            'iso'          => 'ar',
            'flag'         => 'arabic',
            'translatable' => false
        ],
        [
            'id'           => 4,
            'code'         => 'hindi',
            'locale'       => 'hi_IN',
            'iso'          => 'hi',
            'flag'         => 'in',
            'translatable' => false
        ],
        [
            'id'           => 5,
            'code'         => 'spanish',
            'locale'       => 'es_ES',
            'iso'          => 'es',
            'flag'         => 'es',
            'translatable' => true
        ],
        [
            'id'           => 6,
            'code'         => 'bengali',
            'locale'       => 'bn_BD',
            'iso'          => 'bn',
            'flag'         => 'bd',
            'translatable' => false
        ],
        [
            'id'           => 7,
            'code'         => 'french',
            'locale'       => 'fr_FR',
            'iso'          => 'fr',
            'flag'         => 'fr',
            'translatable' => true
        ],
        [
            'id'           => 8,
            'code'         => 'russian',
            'locale'       => 'ru_RU',
            'iso'          => 'ru',
            'flag'         => 'ru',
            'translatable' => false
        ],
        [
            'id'           => 9,
            'code'         => 'portuguese',
            'locale'       => 'pt_PT',
            'iso'          => 'pt',
            'flag'         => 'pt',
            'translatable' => true
        ],
        [
            'id'           => 10,
            'code'         => 'urdu',
            'locale'       => 'ur_PK',
            'iso'          => 'ur',
            'flag'         => 'pk',
            'translatable' => false
        ],
        [
            'id'           => 11,
            'code'         => 'indonesian',
            'locale'       => 'in_ID',
            'iso'          => 'id',
            'flag'         => 'id',
            'translatable' => false
        ],
        [
            'id'           => 12,
            'code'         => 'german',
            'locale'       => 'de_DE',
            'iso'          => 'de',
            'flag'         => 'de',
            'translatable' => true
        ],
        [
            'id'           => 13,
            'code'         => 'japanese',
            'locale'       => 'ja_JP',
            'iso'          => 'ja',
            'flag'         => 'jp',
            'translatable' => true
        ],
        [
            'id'           => 14,
            'code'         => 'nigerian_pidgin',
            'locale'       => 'en_NG',
            'iso'          => 'en',
            'flag'         => 'ng',
            'translatable' => false
        ],
        [
            'id'           => 15,
            'code'         => 'punjabi',
            'locale'       => 'pa_IN',
            'iso'          => 'pa',
            'flag'         => 'pk',
            'translatable' => false
        ],
        [
            'id'           => 16,
            'code'         => 'marathi',
            'locale'       => 'mr_IN',
            'iso'          => 'mr',
            'flag'         => 'in',
            'translatable' => false
        ],
        [
            'id'           => 17,
            'code'         => 'telugu',
            'locale'       => 'te_IN',
            'iso'          => 'te',
            'flag'         => 'in',
            'translatable' => false
        ],
        [
            'id'           => 18,
            'code'         => 'javanese',
            'locale'       => 'jv_JV',
            'iso'          => 'jv',
            'flag'         => 'id',
            'translatable' => false
        ],
        [
            'id'           => 19,
            'code'         => 'turkish',
            'locale'       => 'tr_TR',
            'iso'          => 'tr',
            'flag'         => 'tr',
            'translatable' => false
        ],
        [
            'id'           => 20,
            'code'         => 'tamil',
            'locale'       => 'ta_IN',
            'iso'          => 'ta',
            'flag'         => 'in',
            'translatable' => false
        ],
        [
            'id'           => 21,
            'code'         => 'cantonese',
            'locale'       => 'zh_CN',
            'iso'          => 'zh',
            'flag'         => 'cn',
            'translatable' => false
        ],
        [
            'id'           => 22,
            'code'         => 'vietnamese',
            'locale'       => 'vi_VN',
            'iso'          => 'vi',
            'flag'         => 'vn',
            'translatable' => false
        ],
        [
            'id'           => 23,
            'code'         => 'filipino',
            'locale'       => 'tl_PH',
            'iso'          => 'tl',
            'flag'         => 'ph',
            'translatable' => false
        ],
        [
            'id'           => 24,
            'code'         => 'shanghainese',
            'locale'       => 'zh_CN',
            'iso'          => 'zh',
            'flag'         => 'cn',
            'translatable' => false
        ],
        [
            'id'           => 25,
            'code'         => 'farsi',
            'locale'       => 'fa_IR',
            'iso'          => 'fa',
            'flag'         => 'ir',
            'translatable' => false
        ],
        [
            'id'           => 26,
            'code'         => 'thai',
            'locale'       => 'th_TH',
            'iso'          => 'th',
            'flag'         => 'th',
            'translatable' => false
        ],
        [
            'id'           => 27,
            'code'         => 'swahili',
            'locale'       => 'sw_TZ',
            'iso'          => 'sw',
            'flag'         => 'ke',
            'translatable' => false
        ],
        [
            'id'           => 28,
            'code'         => 'italian',
            'locale'       => 'it_IT',
            'iso'          => 'it',
            'flag'         => 'it',
            'translatable' => true
        ],
        [
            'id'           => 29,
            'code'         => 'kannada',
            'locale'       => 'kn_IN',
            'iso'          => 'kn',
            'flag'         => 'in',
            'translatable' => false
        ],
        [
            'id'           => 30,
            'code'         => 'gujarati',
            'locale'       => 'gu_IN',
            'iso'          => 'gu',
            'flag'         => 'in',
            'translatable' => false
        ],
        [
            'id'           => 31,
            'code'         => 'pashto',
            'locale'       => 'ps_AF',
            'iso'          => 'ps',
            'flag'         => 'af',
            'translatable' => false
        ],
        [
            'id'           => 32,
            'code'         => 'amharic',
            'locale'       => 'am_ET',
            'iso'          => 'am',
            'flag'         => 'et',
            'translatable' => false
        ],
        [
            'id'           => 33,
            'code'         => 'yoruba',
            'locale'       => 'yo_NG',
            'iso'          => 'yo',
            'flag'         => 'ng',
            'translatable' => false
        ],
        [
            'id'           => 34,
            'code'         => 'korean',
            'locale'       => 'ko_KR',
            'iso'          => 'ko',
            'flag'         => 'kr',
            'translatable' => false
        ],
        [
            'id'           => 35,
            'code'         => 'hakka',
            'locale'       => 'zh_CN',
            'iso'          => 'zh',
            'flag'         => 'cn',
            'translatable' => false
        ],
        [
            'id'           => 36,
            'code'         => 'burmese',
            'locale'       => 'my_MM',
            'iso'          => 'my',
            'flag'         => 'mm',
            'translatable' => false
        ],
        [
            'id'           => 37,
            'code'         => 'sundanese',
            'locale'       => 'su_ID',
            'iso'          => 'su',
            'flag'         => 'id',
            'translatable' => false
        ],
        [
            'id'           => 38,
            'code'         => 'polish',
            'locale'       => 'pl_PL',
            'iso'          => 'pl',
            'flag'         => 'pl',
            'translatable' => false
        ],
        [
            'id'           => 39,
            'code'         => 'igbo',
            'locale'       => 'ig_NG',
            'iso'          => 'ig',
            'flag'         => 'ng',
            'translatable' => false
        ],
        [
            'id'           => 40,
            'code'         => 'malaysian',
            'locale'       => 'ms_MS',
            'iso'          => 'ms',
            'flag'         => 'my',
            'translatable' => false
        ],
        [
            'id'           => 41,
            'code'         => 'malayalam',
            'locale'       => 'ml_IN',
            'iso'          => 'ml',
            'flag'         => 'in',
            'translatable' => false
        ],
        [
            'id'           => 42,
            'code'         => 'sindhi',
            'locale'       => 'sd_PK',
            'iso'          => 'sd',
            'flag'         => 'in',
            'translatable' => false
        ],
        [
            'id'           => 43,
            'code'         => 'ukrainian',
            'locale'       => 'uk_UA',
            'iso'          => 'uk',
            'flag'         => 'ua',
            'translatable' => true
        ],
        [
            'id'           => 44,
            'code'         => 'uzbek',
            'locale'       => 'uz_UZ',
            'iso'          => 'uz',
            'flag'         => 'uz',
            'translatable' => false
        ],
        [
            'id'           => 45,
            'code'         => 'dutch',
            'locale'       => 'nl_NL',
            'iso'          => 'nl',
            'flag'         => 'nl',
            'translatable' => false
        ],
        [
            'id'           => 46,
            'code'         => 'lao',
            'locale'       => 'lo_LA',
            'iso'          => 'lo',
            'flag'         => 'la',
            'translatable' => false
        ],
        [
            'id'           => 47,
            'code'         => 'zulu',
            'locale'       => 'zu_ZA',
            'iso'          => 'zu',
            'flag'         => 'za',
            'translatable' => false
        ],
        [
            'id'           => 48,
            'code'         => 'romanian',
            'locale'       => 'ro_RO',
            'iso'          => 'ro',
            'flag'         => 'md',
            'translatable' => false
        ],
        [
            'id'           => 49,
            'code'         => 'fula',
            'locale'       => 'ff_SN',
            'iso'          => 'ff',
            'flag'         => 'ni',
            'translatable' => false
        ],
        [
            'id'           => 50,
            'code'         => 'kurdish',
            'locale'       => 'ku_KR',
            'iso'          => 'ku',
            'flag'         => 'kurdish',
            'translatable' => false
        ],
        [
            'id'           => 51,
            'code'         => 'malagasy',
            'locale'       => 'mg_MG',
            'iso'          => 'mg',
            'flag'         => 'mg',
            'translatable' => false
        ],
        [
            'id'           => 52,
            'code'         => 'nepali',
            'locale'       => 'ne_NP',
            'iso'          => 'np',
            'flag'         => 'np',
            'translatable' => false
        ],
        [
            'id'           => 53,
            'code'         => 'azeri',
            'locale'       => 'az_AZ',
            'iso'          => 'az',
            'flag'         => 'az',
            'translatable' => false
        ],
        [
            'id'           => 54,
            'code'         => 'somali',
            'locale'       => 'so_SO',
            'iso'          => 'so',
            'flag'         => 'so',
            'translatable' => false
        ],
        [
            'id'           => 55,
            'code'         => 'xhosa',
            'locale'       => 'xh_ZA',
            'iso'          => 'xh',
            'flag'         => 'za',
            'translatable' => false
        ],
        [
            'id'           => 56,
            'code'         => 'afrikaans',
            'locale'       => 'af_ZA',
            'iso'          => 'af',
            'flag'         => 'za',
            'translatable' => false
        ],
        [
            'id'           => 57,
            'code'         => 'sinhala',
            'locale'       => 'si_LK',
            'iso'          => 'si',
            'flag'         => 'lk',
            'translatable' => false
        ],
        [
            'id'           => 58,
            'code'         => 'hungarian',
            'locale'       => 'hu_HU',
            'iso'          => 'hu',
            'flag'         => 'hu',
            'translatable' => false
        ],
        [
            'id'           => 59,
            'code'         => 'khmer',
            'locale'       => 'km_KH',
            'iso'          => 'km',
            'flag'         => 'kh',
            'translatable' => false
        ],
        [
            'id'           => 60,
            'code'         => 'shona',
            'locale'       => 'sn_ZW',
            'iso'          => 'sn',
            'flag'         => 'zw',
            'translatable' => false
        ],
        [
            'id'           => 61,
            'code'         => 'greek',
            'locale'       => 'el_GR',
            'iso'          => 'el',
            'flag'         => 'gr',
            'translatable' => false
        ],
        [
            'id'           => 62,
            'code'         => 'swedish',
            'locale'       => 'sv_SE',
            'iso'          => 'sv',
            'flag'         => 'se',
            'translatable' => false
        ],
        [
            'id'           => 63,
            'code'         => 'kazakh',
            'locale'       => 'kk_KK',
            'iso'          => 'kk',
            'flag'         => 'kz',
            'translatable' => false
        ],
        [
            'id'           => 64,
            'code'         => 'serbian',
            'locale'       => 'sr_RS',
            'iso'          => 'sr',
            'flag'         => 'rs',
            'translatable' => false
        ],
        [
            'id'           => 65,
            'code'         => 'haitian_creole',
            'locale'       => 'ht_HT',
            'iso'          => 'ht',
            'flag'         => 'ht',
            'translatable' => false
        ],
        [
            'id'           => 66,
            'code'         => 'czech',
            'locale'       => 'cs_CZ',
            'iso'          => 'cs',
            'flag'         => 'cz',
            'translatable' => false
        ],
        [
            'id'           => 67,
            'code'         => 'belarusian',
            'locale'       => 'be_BE',
            'iso'          => 'be',
            'flag'         => 'by',
            'translatable' => false
        ],
        [
            'id'           => 68,
            'code'         => 'turkmen',
            'locale'       => 'tk_TM',
            'iso'          => 'tk',
            'flag'         => 'tm',
            'translatable' => false
        ],
        [
            'id'           => 69,
            'code'         => 'catalan',
            'locale'       => 'ca_ES',
            'iso'          => 'ca',
            'flag'         => 'es-ca',
            'translatable' => false
        ],
        [
            'id'           => 70,
            'code'         => 'hebrew',
            'locale'       => 'he_IL',
            'iso'          => 'he',
            'flag'         => 'il',
            'translatable' => false
        ],
        [
            'id'           => 71,
            'code'         => 'bulgarian',
            'locale'       => 'bg_BG',
            'iso'          => 'bg',
            'flag'         => 'bg',
            'translatable' => false
        ],
        [
            'id'           => 72,
            'code'         => 'tajik',
            'locale'       => 'tg_TJ',
            'iso'          => 'tg',
            'flag'         => 'tj',
            'translatable' => false
        ],
        [
            'id'           => 73,
            'code'         => 'croatian',
            'locale'       => 'hr_HR',
            'iso'          => 'hr',
            'flag'         => 'hr',
            'translatable' => false
        ],
        [
            'id'           => 74,
            'code'         => 'slovak',
            'locale'       => 'sk_SK',
            'iso'          => 'sk',
            'flag'         => 'sk',
            'translatable' => false
        ],
        [
            'id'           => 75,
            'code'         => 'guarani',
            'locale'       => 'gn_PY',
            'iso'          => 'gn',
            'flag'         => 'py',
            'translatable' => false
        ],
        [
            'id'           => 76,
            'code'         => 'tsonga',
            'locale'       => 'ts_ZA',
            'iso'          => 'ts',
            'flag'         => 'mz',
            'translatable' => false
        ],
        [
            'id'           => 77,
            'code'         => 'kikuyu',
            'locale'       => 'ki_KE',
            'iso'          => 'ki',
            'flag'         => 'ke',
            'translatable' => false
        ],
        [
            'id'           => 78,
            'code'         => 'danish',
            'locale'       => 'da_DK',
            'iso'          => 'da',
            'flag'         => 'dk',
            'translatable' => false
        ],
        [
            'id'           => 79,
            'code'         => 'finnish',
            'locale'       => 'fi_FI',
            'iso'          => 'fi',
            'flag'         => 'fi',
            'translatable' => false
        ],
        [
            'id'           => 80,
            'code'         => 'albanian',
            'locale'       => 'sq_AL',
            'iso'          => 'sq',
            'flag'         => 'al',
            'translatable' => false
        ],
        [
            'id'           => 81,
            'code'         => 'armenian',
            'locale'       => 'hy_AM',
            'iso'          => 'hy',
            'flag'         => 'am',
            'translatable' => false
        ],
        [
            'id'           => 82,
            'code'         => 'norwegian',
            'locale'       => 'no_NO',
            'iso'          => 'no',
            'flag'         => 'no',
            'translatable' => false
        ],
        [
            'id'           => 83,
            'code'         => 'kyrgyz',
            'locale'       => 'ky_KG',
            'iso'          => 'ky',
            'flag'         => 'kg',
            'translatable' => false
        ],
        [
            'id'           => 84,
            'code'         => 'mongolian',
            'locale'       => 'mn_MN',
            'iso'          => 'mn',
            'flag'         => 'mn',
            'translatable' => false
        ],
        [
            'id'           => 85,
            'code'         => 'tatar',
            'locale'       => 'tt_RU',
            'iso'          => 'tt',
            'flag'         => 'ru',
            'translatable' => false
        ],
        [
            'id'           => 86,
            'code'         => 'georgian',
            'locale'       => 'ka_GE',
            'iso'          => 'ka',
            'flag'         => 'ge',
            'translatable' => false
        ],
        [
            'id'           => 87,
            'code'         => 'bosnian',
            'locale'       => 'bs_BA',
            'iso'          => 'bs',
            'flag'         => 'ba',
            'translatable' => false
        ],
        [
            'id'           => 88,
            'code'         => 'slovenian',
            'locale'       => 'sl_SI',
            'iso'          => 'sl',
            'flag'         => 'si',
            'translatable' => false
        ],
        [
            'id'           => 89,
            'code'         => 'galician',
            'locale'       => 'gl_ES',
            'iso'          => 'gl',
            'flag'         => 'galician',
            'translatable' => false
        ],
        [
            'id'           => 90,
            'code'         => 'latin',
            'locale'       => 'la_LA',
            'iso'          => 'la',
            'flag'         => 'va',
            'translatable' => false
        ],
        [
            'id'           => 91,
            'code'         => 'lithuanian',
            'locale'       => 'lt_LT',
            'iso'          => 'lt',
            'flag'         => 'lt',
            'translatable' => false
        ],
        [
            'id'           => 92,
            'code'         => 'irish',
            'locale'       => 'ga_IE',
            'iso'          => 'ga',
            'flag'         => 'ie',
            'translatable' => false
        ],
        [
            'id'           => 93,
            'code'         => 'latvian',
            'locale'       => 'lv_LV',
            'iso'          => 'lv_LV',
            'flag'         => 'lv',
            'translatable' => false
        ],
        [
            'id'           => 94,
            'code'         => 'macedonian',
            'locale'       => 'mk_MK',
            'iso'          => 'mk',
            'flag'         => 'mk',
            'translatable' => false
        ],
        [
            'id'           => 95,
            'code'         => 'limburgish',
            'locale'       => 'li_NL',
            'iso'          => 'li',
            'flag'         => 'nl',
            'translatable' => false
        ],
        [
            'id'           => 96,
            'code'         => 'estonian',
            'locale'       => 'et_EE',
            'iso'          => 'et',
            'flag'         => 'ee',
            'translatable' => false
        ],
        [
            'id'           => 97,
            'code'         => 'sardinian',
            'locale'       => 'sc_IT',
            'iso'          => 'sc',
            'flag'         => 'sardinian',
            'translatable' => false
        ],
        [
            'id'           => 98,
            'code'         => 'welsh',
            'locale'       => 'cy_GB',
            'iso'          => 'cy',
            'flag'         => 'welsh',
            'translatable' => false
        ],
        [
            'id'           => 99,
            'code'         => 'walloon',
            'locale'       => 'wa_BE',
            'iso'          => 'wa',
            'flag'         => 'be',
            'translatable' => false
        ],
        [
            'id'           => 100,
            'code'         => 'maltese',
            'locale'       => 'mt_MT',
            'iso'          => 'mt',
            'flag'         => 'mt',
            'translatable' => false
        ],
        [
            'id'           => 101,
            'code'         => 'luxembourgish',
            'locale'       => 'lb_LU',
            'iso'          => 'lb',
            'flag'         => 'lu',
            'translatable' => false
        ],
        [
            'id'           => 102,
            'code'         => 'frisian',
            'locale'       => 'fy_NL',
            'iso'          => 'fy',
            'flag'         => 'nl',
            'translatable' => false
        ],
        [
            'id'           => 103,
            'code'         => 'samoan',
            'locale'       => 'sm_WS',
            'iso'          => 'sm',
            'flag'         => 'ws',
            'translatable' => false
        ],
        [
            'id'           => 104,
            'code'         => 'icelandic',
            'locale'       => 'is_IS',
            'iso'          => 'is',
            'flag'         => 'is',
            'translatable' => false
        ],
        [
            'id'           => 105,
            'code'         => 'maori',
            'locale'       => 'mi_NZ',
            'iso'          => 'mi',
            'flag'         => 'nz',
            'translatable' => false
        ],
        [
            'id'           => 106,
            'code'         => 'faroese',
            'locale'       => 'fo_FO',
            'iso'          => 'fo',
            'flag'         => 'fo',
            'translatable' => false
        ],
        [
            'id'           => 107,
            'code'         => 'gaelic',
            'locale'       => 'gd_GB',
            'iso'          => 'gd',
            'flag'         => 'gb-sct',
            'translatable' => false
        ],
        [
            'id'           => 108,
            'code'         => 'romansh',
            'locale'       => 'rm_CH',
            'iso'          => 'rm',
            'flag'         => 'ch',
            'translatable' => false
        ]
    ];

    /**
     * List of fields requiring translation
     */
    protected const TRANSLATES = [
        'name'
    ];

    /**
     * @return Collection
     */
    public static function getItems() : Collection
    {
        $appendedItems = static::getAppendedItems();

        $items = new Collection();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            $items->push(
                new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                )
            );
        }

        return $items;
    }

    /**
     * @return Collection
     */
    public static function getTranslatableItems() : Collection
    {
        $appendedItems = static::getAppendedItems();

        $items = new Collection();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['translatable'] === true) {
                $items->push(
                    new LanguageListItem(
                        $appendedItem['id'],
                        $appendedItem['code'],
                        $appendedItem['name'],
                        $appendedItem['locale'],
                        $appendedItem['iso'],
                        $appendedItem['flag'],
                        $appendedItem['translatable']
                    )
                );
            }
        }

        return $items;
    }

    /**
     * @param array|null $ids
     * 
     * @return Collection
     */
    public static function getItemsByIds(
        ?array $ids
    ) : Collection
    {
        $appendedItems = static::getAppendedItemsByIds($ids);

        $items = new Collection();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            $items->push(
                new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                )
            );
        }

        return $items;
    }

    /**
     * @param int|null $id
     *
     * @return LanguageListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?LanguageListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new LanguageListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name'],
                $appendedItem['locale'],
                $appendedItem['iso'],
                $appendedItem['flag'],
                $appendedItem['translatable']
            );
        }

        return null;
    }

    /**
     * @param string|null $code
     *
     * @return LanguageListItem|null
     */
    public static function getItemByISO(
        ?string $code
    ) : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['iso'] == $code) {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getEnglish() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'english') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getChineseMandarin() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'chinese_mandarin') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getArabic() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'arabic') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getHindi() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'hindi') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getSpanish() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'spanish') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getBengali() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'bengali') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getFrench() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'french') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getRussian() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'russian') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getPortuguese() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'portuguese') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getUrdu() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'urdu') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getIndonesian() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'indonesian') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getGerman() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'german') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getJapanese() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'japanese') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getNigerianPidgin() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'nigerian_pidgin') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getPunjabi() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'punjabi') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getMarathi() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'marathi') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getTelugu() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'telugu') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getJavanese() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'javanese') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getTurkish() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'turkish') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getTamil() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'tamil') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getChineseCantonese() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'chinese_cantonese') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getVietnamese() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'vietnamese') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getTagalogFilipino() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'tagalog_filipino') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getWuShanghainese() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'wu_shanghainese') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getFarsi() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'farsi') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getThai() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'thai') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getSwahili() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'swahili') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getItalian() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'italian') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getKannada() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'kannada') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getGujarati() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'gujarati') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getPashto() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'pashto') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getAmharic() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'amharic') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getYoruba() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'yoruba') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getKorean() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'korean') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getHakka() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'hakka') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getBurmese() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'burmese') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getSundanese() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'sundanese') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getPolish() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'polish') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getIgbo() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'igbo') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getMalaysian() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'malaysian') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getMalayalam() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'malayalam') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getSindhi() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'sindhi') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getUkrainian() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'ukrainian') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getUzbek() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'uzbek') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getDutch() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'dutch') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getLao() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'lao') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getZulu() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'zulu') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getRomanian() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'romanian') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getFulah() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'fulah') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getKurdish() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'kurdish') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getMalagasy() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'malagasy') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getNepali() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'nepali') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getAzeri() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'azeri') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getSomali() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'somali') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getXhosa() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'xhosa') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getAfrikaans() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'afrikaans') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getSinhala() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'sinhala') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getHungarian() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'hungarian') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getKhmer() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'khmer') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getShona() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'shona') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getGreek() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'greek') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getSwedish() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'swedish') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getKazakh() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'kazakh') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getSerbian() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'serbian') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getHaitianCreole() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'haitian_creole') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getCzech() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'czech') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getBelarusian() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'belarusian') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getTurkmen() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'turkmen') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getCatalan() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'catalan') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getHebrew() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'hebrew') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getBulgarian() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'bulgarian') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getTajik() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'tajik') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getCroatian() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'croatian') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getSlovak() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'slovak') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getGuarani() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'guarani') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getTsonga() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'tsonga') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getKikuyu() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'kikuyu') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getDanish() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'danish') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getFinnish() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'finnish') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getAlbanian() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'albanian') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getArmenian() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'armenian') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getNorwegian() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'norwegian') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getKyrgyz() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'kyrgyz') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getMongolian() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'mongolian') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getTatar() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'tatar') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getGeorgian() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'georgian') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getBosnian() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'bosnian') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getSlovenian() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'slovenian') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getGalician() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'galician') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getLatin() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'latin') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getLithuanian() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'lithuanian') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getIrish() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'irish') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getLatvian() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'latvian') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getMacedonian() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'macedonian') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getLimburgish() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'limburgish') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getEstonian() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'estonian') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getSardinian() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'sardinian') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getWelsh() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'welsh') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getWalloon() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'walloon') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getMaltese() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'maltese') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getLuxembourgish() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'luxembourgish') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getFrisian() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'frisian') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getSamoan() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'samoan') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getIcelandic() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'icelandic') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getMaori() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'maori') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getFaroese() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'faroese') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getGaelic() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'gaelic') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageListItem|null
     */
    public static function getRomansh() : ?LanguageListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'romansh') {
                return new LanguageListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['locale'],
                    $appendedItem['iso'],
                    $appendedItem['flag'],
                    $appendedItem['translatable']
                );
            }
        }

        return null;
    }
}