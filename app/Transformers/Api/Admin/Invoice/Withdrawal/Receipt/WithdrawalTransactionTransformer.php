<?php

namespace App\Transformers\Api\Admin\Invoice\Withdrawal\Receipt;

use App\Models\MySql\Receipt\Transaction\WithdrawalTransaction;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class WithdrawalTransactionTransformer
 *
 * @package App\Transformers\Api\Admin\Invoice\Withdrawal\Receipt
 */
class WithdrawalTransactionTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'method'
    ];

    /**
     * @param WithdrawalTransaction $withdrawalTransaction
     *
     * @return array
     */
    public function transform(WithdrawalTransaction $withdrawalTransaction) : array
    {
        return [
            'id'              => $withdrawalTransaction->id,
            'external_id'     => $withdrawalTransaction->external_id,
            'amount'          => $withdrawalTransaction->amount,
            'transaction_fee' => $withdrawalTransaction->transaction_fee,
            'description'     => $withdrawalTransaction->description,
            'created_at'      => $withdrawalTransaction->created_at,
        ];
    }

    /**
     * @param WithdrawalTransaction $withdrawalTransaction
     *
     * @return Item|null
     */
    public function includeMethod(WithdrawalTransaction $withdrawalTransaction) : ?Item
    {
        $payoutMethod = $withdrawalTransaction->method;

        return $payoutMethod ? $this->item($payoutMethod, new PaymentMethodTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'withdrawal_transaction';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'withdrawal_transactions';
    }
}
