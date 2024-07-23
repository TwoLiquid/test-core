<?php

namespace App\Transformers\Api\Admin\User\Finance\Buyer\Invoice;

use App\Lists\Invoice\Status\InvoiceStatusList;
use App\Lists\Order\Item\Payment\Status\OrderItemPaymentStatusList;
use App\Lists\Vybe\Type\VybeTypeList;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Item;

/**
 * Class InvoiceListPageTransformer
 *
 * @package App\Transformers\Api\Admin\User\Finance\Buyer\Invoice
 */
class InvoiceListPageTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $buyerInvoices;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $vybeTypes;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $orderItemPaymentStatuses;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $invoiceStatuses;

    /**
     * InvoiceListPageTransformer constructor
     *
     * @param EloquentCollection $buyerInvoices
     * @param EloquentCollection $vybeTypes
     * @param EloquentCollection $orderItemPaymentStatuses,
     * @param EloquentCollection $invoiceStatuses
     */
    public function __construct(
        EloquentCollection $buyerInvoices,
        EloquentCollection $vybeTypes,
        EloquentCollection $orderItemPaymentStatuses,
        EloquentCollection $invoiceStatuses
    )
    {
        $this->buyerInvoices = $buyerInvoices;
        $this->vybeTypes = $vybeTypes;
        $this->orderItemPaymentStatuses = $orderItemPaymentStatuses;
        $this->invoiceStatuses = $invoiceStatuses;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'vybe_types',
        'order_item_payment_statuses',
        'invoice_statuses',
        'filters',
        'buyer_invoices'
    ];

    /**
     * @return array
     */
    public function transform() : array
    {
        return [];
    }

    /**
     * @return Collection|null
     */
    public function includeVybeTypes() : ?Collection
    {
        $vybeTypes = VybeTypeList::getItems();

        return $this->collection($vybeTypes, new VybeTypeTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeOrderItemPaymentStatuses() : ?Collection
    {
        $orderItemPaymentStatuses = OrderItemPaymentStatusList::getItems();

        return $this->collection($orderItemPaymentStatuses, new OrderItemPaymentStatusTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeInvoiceStatuses() : ?Collection
    {
        $invoiceStatuses = InvoiceStatusList::getAllForBuyer();

        return $this->collection($invoiceStatuses, new InvoiceStatusTransformer);
    }

    /**
     * @return Item|null
     */
    public function includeFilters() : ?Item
    {
        return $this->item([], new InvoiceListPageFilterTransformer(
            $this->vybeTypes,
            $this->orderItemPaymentStatuses,
            $this->invoiceStatuses
        ));
    }

    /**
     * @return Collection|null
     */
    public function includeBuyerInvoices() : ?Collection
    {
        return $this->collection($this->buyerInvoices, new InvoiceListTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'buyer_invoice_list';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'buyer_invoice_lists';
    }
}
