<?php

namespace App\Transformers\Api\General\Dashboard\Finance\Seller\Invoice\Tip;

use App\Models\MySql\Tip\TipInvoice;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class TipInvoiceTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Finance\Seller\Invoice\Tip
 */
class TipInvoiceTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'tip',
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
            'id'         => $tipInvoice->id,
            'prefix'     => $tipInvoice->getType()->idPrefix,
            'full_id'    => $tipInvoice->full_id,
            'created_at' => $tipInvoice->created_at ?
                $tipInvoice->created_at->format('Y-m-d\TH:i:s.v\Z') :
                null
        ];
    }

    /**
     * @param TipInvoice $tipInvoice
     *
     * @return Item|null
     */
    public function includeTip(TipInvoice $tipInvoice) : ?Item
    {
        $tip = null;

        if ($tipInvoice->relationLoaded('tip')) {
            $tip = $tipInvoice->tip;
        }

        return $tip ? $this->item($tip, new TipTransformer) : null;
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
