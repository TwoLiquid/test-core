<?php

namespace App\Transformers\Api\Admin\User\Finance\Seller\Tip;

use App\Models\MySql\Tip\TipInvoice;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class TipInvoiceListTransformer
 *
 * @package App\Transformers\Api\Admin\User\Finance\Seller\Tip
 */
class TipInvoiceListTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'item',
        'vybe_type',
        'buyer',
        'status'
    ];

    /**
     * @param TipInvoice $tipInvoice
     *
     * @return array
     */
    public function transform(TipInvoice $tipInvoice) : array
    {
        return [
            'id'           => $tipInvoice->id,
            'full_id'      => $tipInvoice->full_id,
            'price'        => $tipInvoice->tip->item->appearanceCase->price,
            'amount_total' => $tipInvoice->tip->item->amount_total,
            'handling_fee' => $tipInvoice->tip->handling_fee,
            'tip_amount'   => $tipInvoice->tip->amount,
            'created_at'   => $tipInvoice->created_at ?
                $tipInvoice->created_at->format('Y-m-d\TH:i:s.v\Z') :
                null
        ];
    }

    /**
     * @param TipInvoice $tipInvoice
     *
     * @return Item|null
     */
    public function includeItem(TipInvoice $tipInvoice) : ?Item
    {
        $orderItem = null;

        if ($tipInvoice->relationLoaded('tip')) {
            $tip = $tipInvoice->tip;

            if ($tip->relationLoaded('item')) {
                $orderItem = $tip->item;
            }
        }

        return $orderItem ? $this->item($orderItem, new OrderItemTransformer) : null;
    }

    /**
     * @param TipInvoice $tipInvoice
     *
     * @return Item|null
     */
    public function includeVybeType(TipInvoice $tipInvoice) : ?Item
    {
        $vybeType = null;

        if ($tipInvoice->relationLoaded('tip')) {
            $tip = $tipInvoice->tip;

            if ($tip->relationLoaded('item')) {
                $orderItem = $tip->item;

                if ($orderItem->relationLoaded('appearanceCase')) {
                    $appearanceCase = $orderItem->appearanceCase;

                    if ($appearanceCase->relationLoaded('vybe')) {
                        $vybeType = $appearanceCase->vybe->getType();
                    }
                }
            }
        }

        return $vybeType ? $this->item($vybeType, new VybeTypeTransformer) : null;
    }

    /**
     * @param TipInvoice $tipInvoice
     *
     * @return Item|null
     */
    public function includeBuyer(TipInvoice $tipInvoice) : ?Item
    {
        $buyer = null;

        if ($tipInvoice->relationLoaded('tip')) {
            $tip = $tipInvoice->tip;

            if ($tip->relationLoaded('buyer')) {
                $buyer = $tip->buyer;
            }
        }

        return $buyer ? $this->item($buyer, new UserTransformer) : null;
    }

    /**
     * @param TipInvoice $tipInvoice
     *
     * @return Item|null
     */
    public function includeStatus(TipInvoice $tipInvoice) : ?Item
    {
        $status = $tipInvoice->getStatus();

        return $status ? $this->item($status, new InvoiceStatusTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'tip_invoice';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'tip_invoices';
    }
}
