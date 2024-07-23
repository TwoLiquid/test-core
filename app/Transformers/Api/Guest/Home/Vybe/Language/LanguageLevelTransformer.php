<?php

namespace App\Transformers\Api\Guest\Home\Vybe\Language;

use App\Lists\Language\Level\LanguageLevelListItem;
use App\Transformers\BaseTransformer;

/**
 * Class LanguageLevelTransformer
 *
 * @package App\Transformers\Api\Guest\Home\Vybe\Language
 */
class LanguageLevelTransformer extends BaseTransformer
{
    /**
     * @param LanguageLevelListItem $languageLevelListItem
     *
     * @return array
     */
    public function transform(LanguageLevelListItem $languageLevelListItem) : array
    {
        return [
            'id'   => $languageLevelListItem->id,
            'code' => $languageLevelListItem->code,
            'name' => $languageLevelListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'language_level';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'language_levels';
    }
}
