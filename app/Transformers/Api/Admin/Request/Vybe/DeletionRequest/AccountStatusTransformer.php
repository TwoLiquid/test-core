<?php

namespace App\Transformers\Api\Admin\Request\Vybe\DeletionRequest;

use App\Lists\Account\Status\AccountStatusListItem;
use App\Transformers\BaseTransformer;

/**
 * Class AccountStatusTransformer
 *
 * @package App\Transformers\Api\Admin\Request\Vybe\DeletionRequest
 */
class AccountStatusTransformer extends BaseTransformer
{
    /**
     * @param AccountStatusListItem $accountStatusListItem
     *
     * @return array
     */
    public function transform(AccountStatusListItem $accountStatusListItem) : array
    {
        return [
            'id'   => $accountStatusListItem->id,
            'code' => $accountStatusListItem->code,
            'name' => $accountStatusListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'account_status';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'account_statuses';
    }
}
