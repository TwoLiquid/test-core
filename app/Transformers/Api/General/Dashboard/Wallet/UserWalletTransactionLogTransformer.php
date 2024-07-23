<?php

namespace App\Transformers\Api\General\Dashboard\Wallet;

use App\Microservices\Log\Responses\UserWalletTransactionLogResponse;
use App\Transformers\BaseTransformer;

/**
 * Class UserWalletTransactionLogTransformer
 * 
 * @package App\Transformers\Api\General\Dashboard\Wallet
 */
class UserWalletTransactionLogTransformer extends BaseTransformer
{
    /**
     * @param UserWalletTransactionLogResponse $userWalletTransactionLogResponse
     * 
     * @return array
     */
    public function transform(UserWalletTransactionLogResponse $userWalletTransactionLogResponse) : array
    {
        return [
            'id'         => $userWalletTransactionLogResponse->id,
            'amount'     => $userWalletTransactionLogResponse->amount,
            'balance'    => $userWalletTransactionLogResponse->balance,
            'created_at' => $userWalletTransactionLogResponse->createdAt,
            'template'   => $userWalletTransactionLogResponse->template,
            'data'       => $userWalletTransactionLogResponse->data
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_wallet_transaction_log';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_wallet_transaction_logs';
    }
}
