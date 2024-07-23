<?php

namespace App\Transformers\Api\General\Dashboard\Finance\Seller\Invoice\Tip\Pdf;

use App\Models\MySql\Tip\TipTransaction;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class TipTransactionTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Finance\Seller\Invoice\Tip\Pdf
 */
class TipTransactionTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'method'
    ];

    /**
     * @param TipTransaction $tipTransaction
     *
     * @return array
     */
    public function transform(TipTransaction $tipTransaction) : array
    {
        return [
            'id'              => $tipTransaction->id,
            'external_id'     => $tipTransaction->external_id,
            'amount'          => $tipTransaction->amount,
            'transaction_fee' => $tipTransaction->transaction_fee,
            'description'     => $tipTransaction->description,
            'created_at'      => $tipTransaction->created_at ?
                $tipTransaction->created_at->format('Y-m-d\TH:i:s.v\Z') :
                null
        ];
    }

    /**
     * @param TipTransaction $tipTransaction
     *
     * @return Item|null
     */
    public function includeMethod(TipTransaction $tipTransaction) : ?Item
    {
        $paymentMethod = null;

        if ($tipTransaction->relationLoaded('method')) {
            $paymentMethod = $tipTransaction->method;
        }

        return $paymentMethod ? $this->item($paymentMethod, new PaymentMethodTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'tip_transaction';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'tip_transactions';
    }
}
