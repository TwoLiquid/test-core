<?php

namespace App\Transformers\Api\General\Setting\Account;

use App\Lists\Currency\CurrencyList;
use App\Lists\Language\LanguageList;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;

/**
 * Class FormTransformer
 *
 * @package App\Transformers\Api\General\Setting\Account
 */
class FormTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'languages',
        'currencies'
    ];

    /**
     * @return array
     */
    public function transform() : array
    {
        return [];
    }

    /**
     * @return Collection|null
     */
    public function includeLanguages() : ?Collection
    {
        $languages = LanguageList::getTranslatableItems();

        return $this->collection($languages, new LanguageListItemTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeCurrencies() : ?Collection
    {
        $currencies = CurrencyList::getItems();

        return $this->collection($currencies, new CurrencyTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'form';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'forms';
    }
}
