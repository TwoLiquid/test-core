<?php

namespace App\Lists\Language;

/**
 * Class LanguageListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $locale
 * @property string $iso
 * @property string $flag
 * @property bool $translatable
 *
 * @package App\Lists\Language
 */
class LanguageListItem
{
    /**
     * @var int
     */
    public int $id;

    /**
     * @var string
     */
    public string $code;

    /**
     * @var string
     */
    public string $name;

    /**
     * @var string
     */
    public string $locale;

    /**
     * @var string
     */
    public string $iso;

    /**
     * @var string
     */
    public string $flag;

    /**
     * @var bool
     */
    public bool $translatable;

    /**
     * LanguageListItem constructor
     *
     * @param int $id
     * @param string $code
     * @param string $name
     * @param string $locale
     * @param string $iso
     * @param string $flag
     * @param string $translatable
     */
    public function __construct(
        int $id,
        string $code,
        string $name,
        string $locale,
        string $iso,
        string $flag,
        string $translatable
    )
    {
        /** @var int id */
        $this->id = $id;

        /** @var string code */
        $this->code = $code;

        /** @var string name */
        $this->name = $name;

        /** @var string locale */
        $this->locale = $locale;

        /** @var string iso */
        $this->iso = $iso;

        /** @var string flag */
        $this->flag = $flag;

        /** @var string translatable */
        $this->translatable = $translatable;
    }

    /**
     * @return bool
     */
    public function isEnglish() : bool
    {
        return $this->code == 'english';
    }

    /**
     * @return bool
     */
    public function isChineseMandarin() : bool
    {
        return $this->code == 'chinese_mandarin';
    }

    /**
     * @return bool
     */
    public function isArabic() : bool
    {
        return $this->code == 'arabic';
    }

    /**
     * @return bool
     */
    public function isHindi() : bool
    {
        return $this->code == 'hindi';
    }

    /**
     * @return bool
     */
    public function isSpanish() : bool
    {
        return $this->code == 'spanish';
    }

    /**
     * @return bool
     */
    public function isBengali() : bool
    {
        return $this->code == 'bengali';
    }

    /**
     * @return bool
     */
    public function isFrench() : bool
    {
        return $this->code == 'french';
    }

    /**
     * @return bool
     */
    public function isRussian() : bool
    {
        return $this->code == 'russian';
    }

    /**
     * @return bool
     */
    public function isPortuguese() : bool
    {
        return $this->code == 'portuguese';
    }

    /**
     * @return bool
     */
    public function isUrdu() : bool
    {
        return $this->code == 'urdu';
    }

    /**
     * @return bool
     */
    public function isIndonesian() : bool
    {
        return $this->code == 'indonesian';
    }

    /**
     * @return bool
     */
    public function isGerman() : bool
    {
        return $this->code == 'german';
    }

    /**
     * @return bool
     */
    public function isJapanese() : bool
    {
        return $this->code == 'japanese';
    }

    /**
     * @return bool
     */
    public function isNigerianPidgin() : bool
    {
        return $this->code == 'nigerian_pidgin';
    }

    /**
     * @return bool
     */
    public function isPunjabi() : bool
    {
        return $this->code == 'punjabi';
    }

    /**
     * @return bool
     */
    public function isMarathi() : bool
    {
        return $this->code == 'marathi';
    }

    /**
     * @return bool
     */
    public function isTelugu() : bool
    {
        return $this->code == 'telugu';
    }

    /**
     * @return bool
     */
    public function isJavanese() : bool
    {
        return $this->code == 'javanese';
    }

    /**
     * @return bool
     */
    public function isTurkish() : bool
    {
        return $this->code == 'turkish';
    }

    /**
     * @return bool
     */
    public function isTamil() : bool
    {
        return $this->code == 'tamil';
    }

    /**
     * @return bool
     */
    public function isChineseCantonese() : bool
    {
        return $this->code == 'chinese_cantonese';
    }

    /**
     * @return bool
     */
    public function isVietnamese() : bool
    {
        return $this->code == 'vietnamese';
    }

    /**
     * @return bool
     */
    public function isTagalogFilipino() : bool
    {
        return $this->code == 'tagalog_filipino';
    }

    /**
     * @return bool
     */
    public function isWuShanghainese() : bool
    {
        return $this->code == 'wu_shanghainese';
    }

    /**
     * @return bool
     */
    public function isFarsi() : bool
    {
        return $this->code == 'farsi';
    }

    /**
     * @return bool
     */
    public function isThai() : bool
    {
        return $this->code == 'thai';
    }

    /**
     * @return bool
     */
    public function isSwahili() : bool
    {
        return $this->code == 'swahili';
    }

    /**
     * @return bool
     */
    public function isItalian() : bool
    {
        return $this->code == 'italian';
    }

    /**
     * @return bool
     */
    public function isKannada() : bool
    {
        return $this->code == 'kannada';
    }

    /**
     * @return bool
     */
    public function isGujarati() : bool
    {
        return $this->code == 'gujarati';
    }

    /**
     * @return bool
     */
    public function isPashto() : bool
    {
        return $this->code == 'pashto';
    }

    /**
     * @return bool
     */
    public function isAmharic() : bool
    {
        return $this->code == 'amharic';
    }

    /**
     * @return bool
     */
    public function isYoruba() : bool
    {
        return $this->code == 'yoruba';
    }

    /**
     * @return bool
     */
    public function isKorean() : bool
    {
        return $this->code == 'korean';
    }

    /**
     * @return bool
     */
    public function isHakka() : bool
    {
        return $this->code == 'hakka';
    }

    /**
     * @return bool
     */
    public function isBurmese() : bool
    {
        return $this->code == 'burmese';
    }

    /**
     * @return bool
     */
    public function isSundanese() : bool
    {
        return $this->code == 'sundanese';
    }

    /**
     * @return bool
     */
    public function isPolish() : bool
    {
        return $this->code == 'polish';
    }

    /**
     * @return bool
     */
    public function isIgbo() : bool
    {
        return $this->code == 'igbo';
    }

    /**
     * @return bool
     */
    public function isMalaysian() : bool
    {
        return $this->code == 'malaysian';
    }

    /**
     * @return bool
     */
    public function isMalayalam() : bool
    {
        return $this->code == 'malayalam';
    }

    /**
     * @return bool
     */
    public function isSindhi() : bool
    {
        return $this->code == 'sindhi';
    }

    /**
     * @return bool
     */
    public function isUkrainian() : bool
    {
        return $this->code == 'ukrainian';
    }

    /**
     * @return bool
     */
    public function isUzbek() : bool
    {
        return $this->code == 'uzbek';
    }

    /**
     * @return bool
     */
    public function isDutch() : bool
    {
        return $this->code == 'dutch';
    }

    /**
     * @return bool
     */
    public function isLao() : bool
    {
        return $this->code == 'lao';
    }

    /**
     * @return bool
     */
    public function isZulu() : bool
    {
        return $this->code == 'zulu';
    }

    /**
     * @return bool
     */
    public function isRomanian() : bool
    {
        return $this->code == 'romanian';
    }

    /**
     * @return bool
     */
    public function isFulah() : bool
    {
        return $this->code == 'fulah';
    }

    /**
     * @return bool
     */
    public function isKurdish() : bool
    {
        return $this->code == 'kurdish';
    }

    /**
     * @return bool
     */
    public function isMalagasy() : bool
    {
        return $this->code == 'malagasy';
    }

    /**
     * @return bool
     */
    public function isNepali() : bool
    {
        return $this->code == 'nepali';
    }

    /**
     * @return bool
     */
    public function isAzeri() : bool
    {
        return $this->code == 'azeri';
    }

    /**
     * @return bool
     */
    public function isSomali() : bool
    {
        return $this->code == 'somali';
    }

    /**
     * @return bool
     */
    public function isXhosa() : bool
    {
        return $this->code == 'xhosa';
    }

    /**
     * @return bool
     */
    public function isAfrikaans() : bool
    {
        return $this->code == 'afrikaans';
    }

    /**
     * @return bool
     */
    public function isSinhala() : bool
    {
        return $this->code == 'sinhala';
    }

    /**
     * @return bool
     */
    public function isHungarian() : bool
    {
        return $this->code == 'hungarian';
    }

    /**
     * @return bool
     */
    public function isKhmer() : bool
    {
        return $this->code == 'khmer';
    }

    /**
     * @return bool
     */
    public function isShona() : bool
    {
        return $this->code == 'shona';
    }

    /**
     * @return bool
     */
    public function isGreek() : bool
    {
        return $this->code == 'greek';
    }

    /**
     * @return bool
     */
    public function isSwedish() : bool
    {
        return $this->code == 'swedish';
    }

    /**
     * @return bool
     */
    public function isKazakh() : bool
    {
        return $this->code == 'kazakh';
    }

    /**
     * @return bool
     */
    public function isSerbian() : bool
    {
        return $this->code == 'serbian';
    }

    /**
     * @return bool
     */
    public function isHaitianCreole() : bool
    {
        return $this->code == 'haitian_creole';
    }

    /**
     * @return bool
     */
    public function isCzech() : bool
    {
        return $this->code == 'czech';
    }

    /**
     * @return bool
     */
    public function isBelarusian() : bool
    {
        return $this->code == 'belarusian';
    }

    /**
     * @return bool
     */
    public function isTurkmen() : bool
    {
        return $this->code == 'turkmen';
    }

    /**
     * @return bool
     */
    public function isCatalan() : bool
    {
        return $this->code == 'catalan';
    }

    /**
     * @return bool
     */
    public function isHebrew() : bool
    {
        return $this->code == 'hebrew';
    }

    /**
     * @return bool
     */
    public function isBulgarian() : bool
    {
        return $this->code == 'bulgarian';
    }

    /**
     * @return bool
     */
    public function isTajik() : bool
    {
        return $this->code == 'tajik';
    }

    /**
     * @return bool
     */
    public function isCroatian() : bool
    {
        return $this->code == 'croatian';
    }

    /**
     * @return bool
     */
    public function isSlovak() : bool
    {
        return $this->code == 'slovak';
    }

    /**
     * @return bool
     */
    public function isGuarani() : bool
    {
        return $this->code == 'guarani';
    }

    /**
     * @return bool
     */
    public function isTsonga() : bool
    {
        return $this->code == 'tsonga';
    }

    /**
     * @return bool
     */
    public function isKikuyu() : bool
    {
        return $this->code == 'kikuyu';
    }

    /**
     * @return bool
     */
    public function isDanish() : bool
    {
        return $this->code == 'danish';
    }

    /**
     * @return bool
     */
    public function isFinnish() : bool
    {
        return $this->code == 'finnish';
    }

    /**
     * @return bool
     */
    public function isAlbanian() : bool
    {
        return $this->code == 'albanian';
    }

    /**
     * @return bool
     */
    public function isArmenian() : bool
    {
        return $this->code == 'armenian';
    }

    /**
     * @return bool
     */
    public function isNorwegian() : bool
    {
        return $this->code == 'norwegian';
    }

    /**
     * @return bool
     */
    public function isKyrgyz() : bool
    {
        return $this->code == 'kyrgyz';
    }

    /**
     * @return bool
     */
    public function isMongolian() : bool
    {
        return $this->code == 'mongolian';
    }

    /**
     * @return bool
     */
    public function isTatar() : bool
    {
        return $this->code == 'tatar';
    }

    /**
     * @return bool
     */
    public function isGeorgian() : bool
    {
        return $this->code == 'georgian';
    }

    /**
     * @return bool
     */
    public function isBosnian() : bool
    {
        return $this->code == 'bosnian';
    }

    /**
     * @return bool
     */
    public function isSlovenian() : bool
    {
        return $this->code == 'slovenian';
    }

    /**
     * @return bool
     */
    public function isGalician() : bool
    {
        return $this->code == 'galician';
    }

    /**
     * @return bool
     */
    public function isLatin() : bool
    {
        return $this->code == 'latin';
    }

    /**
     * @return bool
     */
    public function isLithuanian() : bool
    {
        return $this->code == 'lithuanian';
    }

    /**
     * @return bool
     */
    public function isIrish() : bool
    {
        return $this->code == 'irish';
    }

    /**
     * @return bool
     */
    public function isLatvian() : bool
    {
        return $this->code == 'latvian';
    }

    /**
     * @return bool
     */
    public function isMacedonian() : bool
    {
        return $this->code == 'macedonian';
    }

    /**
     * @return bool
     */
    public function isLimburgish() : bool
    {
        return $this->code == 'limburgish';
    }

    /**
     * @return bool
     */
    public function isEstonian() : bool
    {
        return $this->code == 'estonian';
    }

    /**
     * @return bool
     */
    public function isSardinian() : bool
    {
        return $this->code == 'sardinian';
    }

    /**
     * @return bool
     */
    public function isWelsh() : bool
    {
        return $this->code == 'welsh';
    }

    /**
     * @return bool
     */
    public function isWalloon() : bool
    {
        return $this->code == 'walloon';
    }

    /**
     * @return bool
     */
    public function isMaltese() : bool
    {
        return $this->code == 'maltese';
    }

    /**
     * @return bool
     */
    public function isLuxembourgish() : bool
    {
        return $this->code == 'luxembourgish';
    }

    /**
     * @return bool
     */
    public function isFrisian() : bool
    {
        return $this->code == 'frisian';
    }

    /**
     * @return bool
     */
    public function isSamoan() : bool
    {
        return $this->code == 'samoan';
    }

    /**
     * @return bool
     */
    public function isIcelandic() : bool
    {
        return $this->code == 'icelandic';
    }

    /**
     * @return bool
     */
    public function isMaori() : bool
    {
        return $this->code == 'maori';
    }

    /**
     * @return bool
     */
    public function isFaroese() : bool
    {
        return $this->code == 'faroese';
    }

    /**
     * @return bool
     */
    public function isGaelic() : bool
    {
        return $this->code == 'gaelic';
    }

    /**
     * @return bool
     */
    public function isRomansh() : bool
    {
        return $this->code == 'romansh';
    }
}