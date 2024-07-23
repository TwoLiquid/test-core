<?php

namespace App\Transformers\Api\Admin\User\Finance\Seller\OrderItem;

use App\Lists\Invoice\Status\InvoiceStatusListItem;
use App\Transformers\BaseTransformer;

/**
 * Class InvoiceStatusTransformer
 *
 * @package App\Transformers\Api\Admin\User\Finance\Seller\OrderItem
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
            'id'    => $invoiceStatusListItem->id,
            'code'  => $invoiceStatusListItem->code,
            'name'  => $invoiceStatusListItem->name,
            'count' => $invoiceStatusListItem->count
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
