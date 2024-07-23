<?php

namespace App\Transformers\Api\Admin\Order\Tip;

use App\Models\MySql\Tip\Tip;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class TipListTransformer
 *
 * @package App\Transformers\Api\Admin\Order\Tip
 */
class TipListTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'item',
        'vybe_type',
        'buyer',
        'seller',
        'method',
        'invoice_buyer',
        'invoice_seller'
    ];

    /**
     * @param Tip $tip
     *
     * @return array
     */
    public function transform(Tip $tip) : array
    {
        return [
            'id'           => $tip->id,
            'handling_fee' => $tip->handling_fee,
            'tip_amount'   => $tip->amount,
            'created_at'   => $tip->created_at ?
                $tip->created_at->format('Y-m-d\TH:i:s.v\Z') :
                null
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
    public function includeVybeType(Tip $tip) : ?Item
    {
        $vybeType = null;

        if ($tip->relationLoaded('item')) {
            $orderItem = $tip->item;

            if ($orderItem->relationLoaded('appearanceCase')) {
                $appearanceCase = $orderItem->appearanceCase;

                if ($appearanceCase->relationLoaded('vybe')) {
                    $vybeType = $appearanceCase->vybe->getType();
                }
            }
        }

        return $vybeType ? $this->item($vybeType, new VybeTypeTransformer) : null;
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
    public function includeSeller(Tip $tip) : ?Item
    {
        $seller = null;

        if ($tip->relationLoaded('seller')) {
            $seller = $tip->seller;
        }

        return $seller ? $this->item($seller, new UserTransformer) : null;
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
    public function includeInvoiceBuyer(Tip $tip) : ?Item
    {
        $invoiceBuyer = null;

        if ($tip->relationLoaded('invoices')) {
            $invoiceBuyer = $tip->getBuyerInvoice();
        }

        return $invoiceBuyer ? $this->item($invoiceBuyer, new TipInvoiceBuyerTransformer) : null;
    }

    /**
     * @param Tip $tip
     *
     * @return Item|null
     */
    public function includeInvoiceSeller(Tip $tip) : ?Item
    {
        $invoiceSeller = null;

        if ($tip->relationLoaded('invoices')) {
            $invoiceSeller = $tip->getSellerInvoice();
        }

        return $invoiceSeller ? $this->item($invoiceSeller, new TipInvoiceSellerTransformer) : null;
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
