<?php

namespace App\Transformers\Api\Admin\User\Finance\Buyer\AddFunds\Receipt;

use App\Models\MySql\Receipt\Transaction\AddFundsTransaction;
use App\Transformers\BaseTransformer;

/**
 * Class AddFundsTransactionTransformer
 *
 * @package App\Transformers\Api\Admin\User\Finance\Buyer\AddFunds\Receipt
 */
class AddFundsTransactionTransformer extends BaseTransformer
{
    /**
     * @param AddFundsTransaction $addFundsTransaction
     *
     * @return array
     */
    public function transform(AddFundsTransaction $addFundsTransaction) : array
    {
        return [
            'id'              => $addFundsTransaction->id,
            'external_id'     => $addFundsTransaction->external_id,
            'amount'          => $addFundsTransaction->amount,
            'transaction_fee' => $addFundsTransaction->transaction_fee,
            'description'     => $addFundsTransaction->description
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'add_funds_transaction';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'add_funds_transactions';
    }
}
