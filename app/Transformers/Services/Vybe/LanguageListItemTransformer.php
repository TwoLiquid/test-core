<?php

namespace App\Transformers\Services\Vybe;

use App\Lists\Language\LanguageListItem;
use App\Transformers\BaseTransformer;

/**
 * Class LanguageListItemTransformer
 *
 * @package App\Transformers\Services\Vybe
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
