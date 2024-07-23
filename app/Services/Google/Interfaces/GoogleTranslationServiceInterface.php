<?php

namespace App\Services\Google\Interfaces;

use App\Lists\Language\LanguageListItem;

/**
 * Interface GoogleTranslationServiceInterface
 *
 * @package App\Services\Google\Interfaces
 */
interface GoogleTranslationServiceInterface
{
    /**
     * This method provides getting data
     *
     * @param string $text
     *
     * @return array
     */
    public function getTranslations(
        string $text
    ) : array;

    /**
     * This method provides getting data
     *
     * @param LanguageListItem $languageListItem
     * @param string $text
     *
     * @return string|null
     */
    public function getTranslation(
        LanguageListItem $languageListItem,
        string $text
    ) : ?string;
}
