<?php

namespace App\Transformers\Api\General\Alert;

use App\Lists\Alert\Type\AlertTypeListItem;
use App\Transformers\BaseTransformer;

/**
 * Class AlertTypeTransformer
 *
 * @package App\Transformers\Api\General\Alert
 */
class AlertTypeTransformer extends BaseTransformer
{
    /**
     * @param AlertTypeListItem $alertTypeListItem
     *
     * @return array
     */
    public function transform(AlertTypeListItem $alertTypeListItem) : array
    {
        return [
            'id'   => $alertTypeListItem->id,
            'code' => $alertTypeListItem->code,
            'name' => $alertTypeListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'alert_type';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'alert_types';
    }
}
