<?php

namespace App\Transformers\Api\General\User\User\Language;

use App\Lists\Language\LanguageListItem;
use App\Transformers\BaseTransformer;

/**
 * Class LanguageListItemTransformer
 *
 * @package App\Transformers\Api\General\User\User\Language
 */
class LanguageListItemTransformer extends BaseTransformer
{
    /**
     * @param LanguageListItem $languageListItem
     *
     * @return array
     */
    public function transform(LanguageListItem $languageListItem) : array
    {
        return [
            'id'     => $languageListItem->id,
            'code'   => $languageListItem->code,
            'name'   => $languageListItem->name,
            'locale' => $languageListItem->locale,
            'iso'    => $languageListItem->iso,
            'flag'   => $languageListItem->flag
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'language';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'languages';
    }
}
