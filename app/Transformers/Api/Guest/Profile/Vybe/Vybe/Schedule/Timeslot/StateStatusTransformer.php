<?php

namespace App\Transformers\Api\Guest\Profile\Vybe\Vybe\Schedule\Timeslot;

use App\Lists\User\State\Status\UserStateStatusListItem;
use App\Transformers\BaseTransformer;

/**
 * Class StateStatusTransformer
 *
 * @package App\Transformers\Api\Guest\Profile\Vybe\Vybe\Schedule\Timeslot
 */
class StateStatusTransformer extends BaseTransformer
{
    /**
     * @param UserStateStatusListItem $userStateStatusListItem
     *
     * @return array
     */
    public function transform(UserStateStatusListItem $userStateStatusListItem) : array
    {
        return [
            'id'   => $userStateStatusListItem->id,
            'code' => $userStateStatusListItem->code,
            'name' => $userStateStatusListItem->name
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
