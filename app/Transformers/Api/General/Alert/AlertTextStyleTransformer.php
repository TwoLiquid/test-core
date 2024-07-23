<?php

namespace App\Transformers\Api\General\Alert;

use App\Lists\Alert\Text\Style\AlertTextStyleListItem;
use App\Transformers\BaseTransformer;

/**
 * Class AlertTextStyleTransformer
 *
 * @package App\Transformers\Api\General\Alert
 */
class AlertTextStyleTransformer extends BaseTransformer
{
    /**
     * @param AlertTextStyleListItem $alertTextStyleListItem
     *
     * @return array
     */
    public function transform(AlertTextStyleListItem $alertTextStyleListItem) : array
    {
        return [
            'id'   => $alertTextStyleListItem->id,
            'code' => $alertTextStyleListItem->code,
            'name' => $alertTextStyleListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'alert_text_style';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'alert_text_styles';
    }
}
