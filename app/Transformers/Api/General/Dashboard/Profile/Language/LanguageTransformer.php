<?php

namespace App\Transformers\Api\General\Dashboard\Profile\Language;

use App\Models\MySql\Language;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class LanguageTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Profile\Language
 */
class LanguageTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'language_level'
    ];

    /**
     * @param Language $language
     *
     * @return array
     */
    public function transform(Language $language) : array
    {
        $language = $language->getLanguage();

        return [
            'id'     => $language->id,
            'code'   => $language->code,
            'name'   => $language->name,
            'locale' => $language->locale,
            'iso'    => $language->iso,
            'flag'   => $language->flag
        ];
    }

    /**
     * @param Language $language
     *
     * @return Item|null
     */
    public function includeLanguageLevel(Language $language) : ?Item
    {
        $languageLevel = $language->getLevel();

        return $languageLevel ? $this->item($languageLevel, new LanguageLevelTransformer) : null;
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
