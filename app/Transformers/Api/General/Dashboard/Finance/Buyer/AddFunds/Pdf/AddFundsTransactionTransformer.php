<?php

namespace App\Transformers\Api\General\Dashboard\Finance\Buyer\AddFunds\Pdf;

use App\Models\MySql\Receipt\Transaction\AddFundsTransaction;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class AddFundsTransactionTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Finance\Buyer\AddFunds\Pdf
 */
class AddFundsTransactionTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'method'
    ];

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
            'description'     => $addFundsTransaction->description,
            'created_at'      => $addFundsTransaction->created_at
        ];
    }

    /**
     * @param AddFundsTransaction $addFundsTransaction
     *
     * @return Item|null
     */
    public function includeMethod(AddFundsTransaction $addFundsTransaction) : ?Item
    {
        $paymentMethod = null;

        if ($addFundsTransaction->relationLoaded('method')) {
            $paymentMethod = $addFundsTransaction->method;
        }

        return $paymentMethod ? $this->item($paymentMethod, new PaymentMethodTransformer) : null;
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
