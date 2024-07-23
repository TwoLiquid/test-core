<?php

namespace App\Transformers\Api\Guest\User\User\Vybe;

use App\Lists\Gender\GenderListItem;
use App\Transformers\BaseTransformer;

/**
 * Class GenderTransformer
 *
 * @package App\Transformers\Api\Guest\User\User\Vybe
 */
class GenderTransformer extends BaseTransformer
{
    /**
     * @param GenderListItem $genderListItem
     *
     * @return array
     */
    public function transform(GenderListItem $genderListItem) : array
    {
        return [
            'id'   => $genderListItem->id,
            'code' => $genderListItem->code,
            'name' => $genderListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'gender';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'genders';
    }
}
