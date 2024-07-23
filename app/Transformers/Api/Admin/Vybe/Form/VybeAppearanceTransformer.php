<?php

namespace App\Transformers\Api\Admin\Vybe\Form;

use App\Lists\Vybe\Appearance\VybeAppearanceListItem;
use App\Transformers\BaseTransformer;

/**
 * Class VybeAppearanceTransformer
 *
 * @package App\Transformers\Api\Admin\Vybe\Form
 */
class VybeAppearanceTransformer extends BaseTransformer
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
