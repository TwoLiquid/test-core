<?php

namespace App\Transformers\Api\General\Tip;

use App\Models\MySql\Tip\Tip;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class TipTransformer
 *
 * @package App\Transformers\Api\General\Tip
 */
class TipTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'method',
        'invoices'
    ];

    /**
     * @param Tip $tip
     *
     * @return array
     */
    public function transform(Tip $tip) : array
    {
        return [
            'id'              => $tip->id,
            'amount'          => $tip->amount,
            'amount_earned'   => $tip->amount_earned,
            'amount_tax'      => $tip->amount_tax,
            'handling_fee'    => $tip->handling_fee,
            'payment_fee'     => $tip->payment_fee,
            'payment_fee_tax' => $tip->payment_fee_tax,
            'comment'         => $tip->comment,
            'paid_at'         => $tip->paid_at
        ];
    }

    /**
     * @param Tip $tip
     *
     * @return Item|null
     */
    public function includeMethod(Tip $tip) : ?Item
    {
        $paymentMethod = null;

        if ($tip->relationLoaded('method')) {
            $paymentMethod = $tip->method;
        }

        return $paymentMethod ? $this->item($paymentMethod, new PaymentMethodTransformer) : null;
    }

    /**
     * @param Tip $tip
     *
     * @return Collection|null
     */
    public function includeInvoices(Tip $tip) : ?Collection
    {
        $invoices = null;

        if ($tip->relationLoaded('invoices')) {
            $invoices = $tip->invoices;
        }

        return $invoices ? $this->collection($invoices, new TipInvoiceTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'tip';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'tips';
    }
}
