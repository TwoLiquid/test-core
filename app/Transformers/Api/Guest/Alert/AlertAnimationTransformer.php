<?php

namespace App\Transformers\Api\Guest\Alert;

use App\Lists\Alert\Animation\AlertAnimationListItem;
use App\Transformers\BaseTransformer;

/**
 * Class AlertAnimationTransformer
 *
 * @package App\Transformers\Api\Guest\Alert
 */
class AlertAnimationTransformer extends BaseTransformer
{
    /**
     * @param AlertAnimationListItem $alertAnimationListItem
     *
     * @return array
     */
    public function transform(AlertAnimationListItem $alertAnimationListItem) : array
    {
        return [
            'id'   => $alertAnimationListItem->id,
            'code' => $alertAnimationListItem->code,
            'name' => $alertAnimationListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'alert_animation';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'alert_animations';
    }
}
