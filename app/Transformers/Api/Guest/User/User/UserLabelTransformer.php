<?php

namespace App\Transformers\Api\Guest\User\User;

use App\Lists\User\Label\UserLabelListItem;
use App\Transformers\BaseTransformer;

/**
 * Class UserLabelTransformer
 *
 * @package App\Transformers\Api\Guest\User\User
 */
class UserLabelTransformer extends BaseTransformer
{
    /**
     * @param UserLabelListItem $userLabelListItem
     *
     * @return array
     */
    public function transform(UserLabelListItem $userLabelListItem) : array
    {
        return [
            'id'      => $userLabelListItem->id,
            'code'    => $userLabelListItem->code,
            'name'    => $userLabelListItem->name,
            'comment' => $userLabelListItem->comment
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_label';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_labels';
    }
}
