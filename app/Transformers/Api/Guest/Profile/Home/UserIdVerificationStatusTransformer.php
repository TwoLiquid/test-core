<?php

namespace App\Transformers\Api\Guest\Profile\Home;

use App\Lists\User\IdVerification\Status\UserIdVerificationStatusListItem;
use App\Transformers\BaseTransformer;

/**
 * Class UserIdVerificationStatusTransformer
 *
 * @package App\Transformers\Api\Guest\Profile\Home
 */
class UserIdVerificationStatusTransformer extends BaseTransformer
{
    /**
     * @param UserIdVerificationStatusListItem $userIdVerificationStatusListItem
     *
     * @return array
     */
    public function transform(UserIdVerificationStatusListItem $userIdVerificationStatusListItem) : array
    {
        return [
            'id'   => $userIdVerificationStatusListItem->id,
            'code' => $userIdVerificationStatusListItem->code,
            'name' => $userIdVerificationStatusListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_id_verification_status';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_id_verification_statuses';
    }
}
