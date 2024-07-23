<?php

namespace App\Services\Google;

use App\Lists\Language\LanguageList;
use App\Lists\Language\LanguageListItem;
use App\Services\Google\Interfaces\GoogleTranslationServiceInterface;
use Dedicated\GoogleTranslate\TranslateException;
use Dedicated\GoogleTranslate\Translator;

/**
 * Class GoogleTranslationService
 *
 * @package App\Services\Google
 */
class GoogleTranslationService implements GoogleTranslationServiceInterface
{
    /**
     * @var Translator
     */
    protected Translator $translator;

    /**
     * GoogleTranslationService constructor
     */
    public function __construct()
    {
        /** @var Translator translator */
        $this->translator = new Translator();
    }

    /**
     * @param string $text
     *
     * @return array
     *
     * @throws TranslateException
     */
    public function getTranslations(
        string $text
    ) : array
    {
        /**
         * Preparing translations variable
         */
        $translations = [];

        /** @var LanguageListItem $languageListItem */
        foreach (LanguageList::getTranslatableItems() as $languageListItem) {

            /**
             * Checking language
             */
            if ($languageListItem->isEnglish()) {

                /**
                 * Setting single translation
                 */
                $translations[$languageListItem->iso] = trim($text);
            } else {

                /**
                 * Setting translations
                 */
                $result = $this->translator
                    ->setSourceLang('en')
                    ->setTargetLang($languageListItem->iso)
                    ->translate(
                        trim($text),
                        $languageListItem->iso
                    );

                $translations[$languageListItem->iso] = $result;
            }
        }

        return $translations;
    }

    /**
     * @param LanguageListItem $languageListItem
     * @param string $text
     *
     * @return string|null
     *
     * @throws TranslateException
     */
    public function getTranslation(
        LanguageListItem $languageListItem,
        string $text
    ) : ?string
    {
        /**
         * Setting translations
         */
        return $this->translator
            ->setSourceLang(LanguageList::getEnglish()->iso)
            ->setTargetLang($languageListItem->iso)
            ->translate(
                trim($text),
                $languageListItem->iso
            );
    }
}
