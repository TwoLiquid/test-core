<?php

namespace App\Transformers\Api\Admin\Invoice\Withdrawal\Receipt;

use App\Lists\Withdrawal\Receipt\Status\WithdrawalReceiptStatusList;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Item;

/**
 * Class WithdrawalReceiptListPageTransformer
 *
 * @package App\Transformers\Api\Admin\Invoice\Withdrawal\Receipt
 */
class WithdrawalReceiptListPageTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $withdrawalReceipts;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $withdrawalReceiptStatuses;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $payoutMethods;

    /**
     * WithdrawalReceiptListPageTransformer constructor
     *
     * @param EloquentCollection $withdrawalReceipts
     * @param EloquentCollection $withdrawalReceiptStatuses
     * @param EloquentCollection $payoutMethods
     */
    public function __construct(
        EloquentCollection $withdrawalReceipts,
        EloquentCollection $withdrawalReceiptStatuses,
        EloquentCollection $payoutMethods
    )
    {
        $this->withdrawalReceipts = $withdrawalReceipts;
        $this->withdrawalReceiptStatuses = $withdrawalReceiptStatuses;
        $this->payoutMethods = $payoutMethods;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'statuses',
        'filters',
        'withdrawal_receipts',
        'payout_methods'
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
    public function includeStatuses() : ?Collection
    {
        $withdrawalReceiptStatuses = WithdrawalReceiptStatusList::getItems();

        return $this->collection($withdrawalReceiptStatuses, new WithdrawalReceiptStatusTransformer);
    }

    /**
     * @return Item|null
     */
    public function includeFilters() : ?Item
    {
        return $this->item([], new WithdrawalReceiptListPageFilterTransformer(
            $this->withdrawalReceiptStatuses
        ));
    }

    /**
     * @return Collection|null
     */
    public function includeWithdrawalReceipts() : ?Collection
    {
        return $this->collection($this->withdrawalReceipts, new WithdrawalReceiptListTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includePayoutMethods() : ?Collection
    {
        return $this->collection($this->payoutMethods, new PaymentMethodTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'withdrawal_receipt_list';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'withdrawal_receipt_lists';
    }
}
