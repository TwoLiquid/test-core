<?php

namespace App\Transformers\Api\General\Tip;

use App\Lists\Invoice\Type\InvoiceTypeListItem;
use App\Transformers\BaseTransformer;

/**
 * Class InvoiceTypeTransformer
 *
 * @package App\Transformers\Api\General\Tip
 */
class InvoiceTypeTransformer extends BaseTransformer
{
    /**
     * @param InvoiceTypeListItem $invoiceTypeListItem
     *
     * @return array
     */
    public function transform(InvoiceTypeListItem $invoiceTypeListItem) : array
    {
        return [
            'id'   => $invoiceTypeListItem->id,
            'name' => $invoiceTypeListItem->name,
            'code' => $invoiceTypeListItem->code
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'invoice_type';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'invoice_types';
    }
}
