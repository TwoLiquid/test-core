<?php

namespace App\Transformers\Api\General\Alert;

use App\Lists\Alert\Logo\Align\AlertLogoAlignListItem;
use App\Transformers\BaseTransformer;

/**
 * Class AlertLogoAlignTransformer
 *
 * @package App\Transformers\Api\General\Alert
 */
class AlertLogoAlignTransformer extends BaseTransformer
{
    /**
     * @param AlertLogoAlignListItem $alertLogoAlignListItem
     *
     * @return array
     */
    public function transform(AlertLogoAlignListItem $alertLogoAlignListItem) : array
    {
        return [
            'id'   => $alertLogoAlignListItem->id,
            'code' => $alertLogoAlignListItem->code,
            'name' => $alertLogoAlignListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'alert_logo_align';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'alert_logo_aligns';
    }
}
