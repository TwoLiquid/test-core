<?php

namespace App\Transformers\Api\Admin\Order\OrderItem;

use App\Models\MySql\Order\OrderInvoice;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class OrderInvoiceSellerTransformer
 *
 * @package App\Transformers\Api\Admin\Order
 */
class OrderItemInvoiceSellerTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'status'
    ];

    /**
     * @param OrderInvoice $orderInvoice
     *
     * @return array
     */
    public function transform(OrderInvoice $orderInvoice) : array
    {
        return [
            'id'            => $orderInvoice->id,
            'full_id'       => $orderInvoice->full_id
        ];
    }

    /**
     * @param OrderInvoice $orderInvoice
     *
     * @return Item|null
     */
    public function includeStatus(OrderInvoice $orderInvoice) : ?Item
    {
        $invoiceStatus = $orderInvoice->getStatus();

        return $invoiceStatus ? $this->item($invoiceStatus, new InvoiceStatusTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'invoice';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'invoices';
    }
}
