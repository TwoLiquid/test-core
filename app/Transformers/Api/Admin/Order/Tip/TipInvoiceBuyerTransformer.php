<?php

namespace App\Transformers\Api\Admin\Order\Tip;

use App\Models\MySql\Tip\TipInvoice;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class TipInvoiceBuyerTransformer
 *
 * @package App\Transformers\Api\Admin\Order\Tip
 */
class TipInvoiceBuyerTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
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
            'id'      => $tipInvoice->id,
            'full_id' => $tipInvoice->full_id
        ];
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
        return 'tip_buyer_invoice';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'tip_buyer_invoices';
    }
}
