<?php

namespace App\Transformers\Api\General\Alert;

use App\Lists\Alert\Align\AlertAlignListItem;
use App\Transformers\BaseTransformer;

/**
 * Class AlertAlignTransformer
 *
 * @package App\Transformers\Api\General\Alert
 */
class AlertAlignTransformer extends BaseTransformer
{
    /**
     * @param AlertAlignListItem $alertAlignListItem
     *
     * @return array
     */
    public function transform(AlertAlignListItem $alertAlignListItem) : array
    {
        return [
            'id'   => $alertAlignListItem->id,
            'code' => $alertAlignListItem->code,
            'name' => $alertAlignListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'alert_align';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'alert_aligns';
    }
}
