<?php

namespace App\Transformers\Api\Guest\Navbar;

use App\Lists\Currency\CurrencyListItem;
use App\Transformers\BaseTransformer;

/**
 * Class CurrencyTransformer
 *
 * @package App\Transformers\Api\Guest\Navbar
 */
class CurrencyTransformer extends BaseTransformer
{
    /**
     * @param CurrencyListItem $currencyListItem
     *
     * @return array
     */
    public function transform(CurrencyListItem $currencyListItem) : array
    {
        return [
            'id'   => $currencyListItem->id,
            'code' => $currencyListItem->code,
            'name' => $currencyListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'currency';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'currencies';
    }
}