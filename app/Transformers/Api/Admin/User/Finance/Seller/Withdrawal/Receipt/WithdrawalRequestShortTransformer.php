<?php

namespace App\Transformers\Api\Admin\User\Finance\Seller\Withdrawal\Receipt;

use App\Models\MongoDb\WithdrawalRequest;
use App\Transformers\BaseTransformer;

/**
 * Class WithdrawalRequestShortTransformer
 *
 * @package App\Transformers\Api\Admin\User\Finance\Seller\Withdrawal\Receipt
 */
class WithdrawalRequestShortTransformer extends BaseTransformer
{
    /**
     * @param WithdrawalRequest $withdrawalRequest
     *
     * @return array
     */
    public function transform(WithdrawalRequest $withdrawalRequest) : array
    {
        return [
            'id' => $withdrawalRequest->_id
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'withdrawal_request';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'withdrawal_requests';
    }
}
