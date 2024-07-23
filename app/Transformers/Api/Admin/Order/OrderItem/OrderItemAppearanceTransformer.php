<?php

namespace App\Transformers\Api\Admin\Order\OrderItem;

use App\Lists\Vybe\Appearance\VybeAppearanceListItem;
use App\Transformers\BaseTransformer;

/**
 * Class OrderItemAppearanceTransformer
 *
 * @package App\Transformers\Api\Admin\Order\OrderItem
 */
class OrderItemAppearanceTransformer extends BaseTransformer
{
    /**
     * @param VybeAppearanceListItem $vybeAppearanceListItem
     *
     * @return array
     */
    public function transform(VybeAppearanceListItem $vybeAppearanceListItem) : array
    {
        return [
            'id'   => $vybeAppearanceListItem->id,
            'code' => $vybeAppearanceListItem->code,
            'name' => $vybeAppearanceListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe_appearance';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybe_appearances';
    }
}
