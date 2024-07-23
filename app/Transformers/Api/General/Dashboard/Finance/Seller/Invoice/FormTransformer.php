<?php

namespace App\Transformers\Api\General\Dashboard\Finance\Seller\Invoice;

use App\Lists\Invoice\Status\InvoiceStatusList;
use App\Lists\Order\Item\Status\OrderItemStatusList;
use App\Lists\Vybe\Type\VybeTypeList;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;

/**
 * Class FormTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Finance\Seller\Invoice
 */
class FormTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'vybe_types',
        'order_item_statuses',
        'invoice_statuses'
    ];

    /**
     * @return array
     */
    public function transform() : array
    {
        return [];
    }

    /**
     * @return Collection
     */
    public function includeVybeTypes() : Collection
    {
        $vybeTypes = VybeTypeList::getItems();

        return $this->collection($vybeTypes, new VybeTypeTransformer);
    }

    /**
     * @return Collection
     */
    public function includeOrderItemStatuses() : Collection
    {
        $orderItemStatuses = OrderItemStatusList::getItems();

        return $this->collection($orderItemStatuses, new OrderItemStatusTransformer);
    }

    /**
     * @return Collection
     */
    public function includeInvoiceStatuses() : Collection
    {
        $invoiceStatuses = InvoiceStatusList::getAllForSeller();

        return $this->collection($invoiceStatuses, new InvoiceStatusTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'form';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'forms';
    }
}
