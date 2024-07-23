<?php

namespace App\Transformers\Api\General\Cart\Timeslot;

use App\Lists\User\State\Status\UserStateStatusListItem;
use App\Transformers\BaseTransformer;

/**
 * Class StateStatusTransformer
 *
 * @package App\Transformers\Api\General\Cart\Timeslot
 */
class StateStatusTransformer extends BaseTransformer
{
    /**
     * @param UserStateStatusListItem $stateStatusListItem
     *
     * @return array
     */
    public function transform(UserStateStatusListItem $stateStatusListItem) : array
    {
        return [
            'id'    => $stateStatusListItem->id,
            'code'  => $stateStatusListItem->code,
            'name'  => $stateStatusListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'state_status';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'state_statuses';
    }
}
