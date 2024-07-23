<?php

namespace App\Transformers\Api\Admin\Invoice\Tip\Buyer;

use App\Models\MySql\Tip\Tip;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class TipTransformer
 *
 * @package App\Transformers\Api\Admin\Invoice\Tip\Buyer
 */
class TipTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'item',
        'method',
        'buyer',
        'vybe',
        'transactions'
    ];

    /**
     * @param Tip $tip
     *
     * @return array
     */
    public function transform(Tip $tip) : array
    {
        return [
            'amount'            => $tip->amount,
            'amount_tax'        => $tip->amount_tax,
            'amount_earned'     => $tip->amount_earned,
            'handling_fee'      => $tip->handling_fee,
            'payment_fee'       => $tip->payment_fee,
            'payment_fee_tax'   => $tip->payment_fee_tax,
            'payment_fee_total' => number_format(
                array_sum([
                    $tip->payment_fee,
                    $tip->payment_fee_tax
                ]), 2
            ),
            'comment'           => $tip->comment,
            'paid_at'           => $tip->paid_at,
            'total'             => number_format(
                array_sum([
                    $tip->amount +
                    $tip->payment_fee +
                    $tip->payment_fee_tax +
                    $tip->amount_tax
                ]), 2
            )
        ];
    }

    /**
     * @param Tip $tip
     *
     * @return Item|null
     */
    public function includeItem(Tip $tip) : ?Item
    {
        $orderItem = null;

        if ($tip->relationLoaded('item')) {
            $orderItem = $tip->item;
        }

        return $orderItem ? $this->item($orderItem, new OrderItemTransformer) : null;
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
     * @return Item|null
     */
    public function includeBuyer(Tip $tip) : ?Item
    {
        $buyer = null;

        if ($tip->relationLoaded('buyer')) {
            $buyer = $tip->buyer;
        }

        return $buyer ? $this->item($buyer, new UserTransformer) : null;
    }

    /**
     * @param Tip $tip
     *
     * @return Item|null
     */
    public function includeVybe(Tip $tip) : ?Item
    {
        $vybe = null;

        if ($tip->relationLoaded('item')) {
            $orderItem = $tip->item;

            if ($orderItem->relationLoaded('vybe')) {
                $vybe = $orderItem->vybe;
            }
        }

        return $vybe ? $this->item($vybe, new VybeTransformer) : null;
    }

    /**
     * @param Tip $tip
     *
     * @return Collection|null
     */
    public function includeTransactions(Tip $tip) : ?Collection
    {
        $tipTransactions = null;

        if ($tip->relationLoaded('transactions')) {
            $tipTransactions = $tip->transactions;
        }

        return $tipTransactions ? $this->collection($tipTransactions, new TipTransactionTransformer) : null;
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
