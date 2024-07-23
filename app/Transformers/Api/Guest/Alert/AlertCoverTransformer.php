<?php

namespace App\Transformers\Api\Guest\Alert;

use App\Lists\Alert\Cover\AlertCoverListItem;
use App\Transformers\BaseTransformer;

/**
 * Class AlertCoverTransformer
 *
 * @package App\Transformers\Api\Guest\Alert
 */
class AlertCoverTransformer extends BaseTransformer
{
    /**
     * @param AlertCoverListItem $alertCoverListItem
     *
     * @return array
     */
    public function transform(AlertCoverListItem $alertCoverListItem) : array
    {
        return [
            'id'   => $alertCoverListItem->id,
            'code' => $alertCoverListItem->code,
            'name' => $alertCoverListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'alert_cover';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'alert_covers';
    }
}
