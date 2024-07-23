<?php

namespace App\Transformers\Api\General\Dashboard\Finance\Seller\Withdrawal;

use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class WithdrawalReceiptPageTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Finance\Seller\Withdrawal
 */
class WithdrawalReceiptPageTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $withdrawalReceipts;

    /**
     * WithdrawalReceiptPageTransformer constructor
     */
    public function __construct(
        EloquentCollection $withdrawalReceipts
    )
    {
        /** @var EloquentCollection withdrawalReceipts */
        $this->withdrawalReceipts = $withdrawalReceipts;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'form',
        'withdrawal_receipts'
    ];

    /**
     * @return array
     */
    public function transform() : array
    {
        return [];
    }

    /**
     * @return Item|null
     */
    public function includeForm() : ?Item
    {
        return $this->item([], new FormTransformer);
    }

    /**
     * @return Collection
     */
    public function includeWithdrawalReceipts() : Collection
    {
        return $this->collection($this->withdrawalReceipts, new WithdrawalReceiptTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'withdrawal_receipt_page';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'withdrawal_receipt_pages';
    }
}
