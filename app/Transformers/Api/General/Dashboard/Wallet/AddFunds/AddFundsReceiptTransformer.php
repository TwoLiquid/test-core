<?php

namespace App\Transformers\Api\General\Dashboard\Wallet\AddFunds;

use App\Models\MySql\Receipt\AddFundsReceipt;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class AddFundsReceiptTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Wallet\AddFunds
 */
class AddFundsReceiptTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'user',
        'method',
        'status',
        'transactions'
    ];

    /**
     * @param AddFundsReceipt $addFundsReceipt
     *
     * @return array
     */
    public function transform(AddFundsReceipt $addFundsReceipt) : array
    {
        return [
            'id'           => $addFundsReceipt->id,
            'description'  => $addFundsReceipt->description,
            'amount'       => $addFundsReceipt->amount,
            'amount_total' => $addFundsReceipt->amount_total,
            'payment_fee'  => $addFundsReceipt->payment_fee
        ];
    }

    /**
     * @param AddFundsReceipt $addFundsReceipt
     *
     * @return Item|null
     */
    public function includeUser(AddFundsReceipt $addFundsReceipt) : ?Item
    {
        $user = null;

        if ($addFundsReceipt->relationLoaded('user')) {
            $user = $addFundsReceipt->user;
        }

        return $user ? $this->item($user, new UserTransformer) : null;
    }

    /**
     * @param AddFundsReceipt $addFundsReceipt
     *
     * @return Item|null
     */
    public function includeMethod(AddFundsReceipt $addFundsReceipt) : ?Item
    {
        $paymentMethod = null;

        if ($addFundsReceipt->relationLoaded('method')) {
            $paymentMethod = $addFundsReceipt->method;
        }

        return $paymentMethod ? $this->item($paymentMethod, new PaymentMethodTransformer) : null;
    }

    /**
     * @param AddFundsReceipt $addFundsReceipt
     *
     * @return Item|null
     */
    public function includeStatus(AddFundsReceipt $addFundsReceipt) : ?Item
    {
        $status = $addFundsReceipt->getStatus();

        return $status ? $this->item($status, new AddFundsReceiptStatusTransformer) : null;
    }

    /**
     * @param AddFundsReceipt $addFundsReceipt
     *
     * @return Item|null
     */
    public function includeTransactions(AddFundsReceipt $addFundsReceipt) : ?Collection
    {
        $transactions = null;

        if ($addFundsReceipt->relationLoaded('transactions')) {
            $transactions = $addFundsReceipt->transactions;
        }

        return $transactions ? $this->collection($transactions, new AddFundsTransactionTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'add_funds_receipt';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'add_funds_receipts';
    }
}
