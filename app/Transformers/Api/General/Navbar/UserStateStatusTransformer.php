<?php

namespace App\Transformers\Api\General\Navbar;

use App\Lists\User\State\Status\UserStateStatusListItem;
use App\Transformers\BaseTransformer;

/**
 * Class UserStateStatusTransformer
 *
 * @package App\Transformers\Api\General\Navbar
 */
class UserStateStatusTransformer extends BaseTransformer
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
        return 'user_state_status';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_state_statuses';
    }
}
