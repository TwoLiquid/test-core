<?php

namespace App\Transformers\Api\Admin\Order\OrderItem;

use App\Lists\Invoice\Type\InvoiceTypeListItem;
use App\Transformers\BaseTransformer;

/**
 * Class InvoiceTypeTransformer
 *
 * @package App\Transformers\Api\Admin\Order\OrderItem
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
            'code' => $invoiceTypeListItem->code,
            'name' => $invoiceTypeListItem->name
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
