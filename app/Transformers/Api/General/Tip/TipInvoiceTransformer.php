<?php

namespace App\Transformers\Api\General\Tip;

use App\Models\MySql\Tip\TipInvoice;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class TipInvoiceTransformer
 *
 * @package App\Transformers\Api\General\Tip
 */
class TipInvoiceTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'type',
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
            'id' => $tipInvoice->id
        ];
    }

    /**
     * @param TipInvoice $tipInvoice
     *
     * @return Item|null
     */
    public function includeType(TipInvoice $tipInvoice) : ?Item
    {
        $type = $tipInvoice->getType();

        return $type ? $this->item($type, new InvoiceTypeTransformer) : null;
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
