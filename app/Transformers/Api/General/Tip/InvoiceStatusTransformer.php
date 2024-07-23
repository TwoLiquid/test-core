<?php

namespace App\Transformers\Api\General\Tip;

use App\Lists\Invoice\Status\InvoiceStatusListItem;
use App\Transformers\BaseTransformer;

/**
 * Class InvoiceStatusTransformer
 *
 * @package App\Transformers\Api\General\Tip
 */
class InvoiceStatusTransformer extends BaseTransformer
{
    /**
     * @param InvoiceStatusListItem $invoiceStatusListItem
     *
     * @return array
     */
    public function transform(InvoiceStatusListItem $invoiceStatusListItem) : array
    {
        return [
            'id'   => $invoiceStatusListItem->id,
            'name' => $invoiceStatusListItem->name,
            'code' => $invoiceStatusListItem->code
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'invoice_status';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'invoice_statuses';
    }
}
