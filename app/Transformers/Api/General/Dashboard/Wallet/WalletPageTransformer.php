<?php

namespace App\Transformers\Api\General\Dashboard\Wallet;

use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Item;

/**
 * Class WalletPageTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Wallet
 */
class WalletPageTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $userBalances;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $userWalletTransactionLogs;

    /**
     * @var array
     */
    protected array $pagination;

    /**
     * WalletPageTransformer constructor
     *
     * @param EloquentCollection $userBalances
     * @param EloquentCollection $userWalletTransactionLogs
     * @param array $pagination
     */
    public function __construct(
        EloquentCollection $userBalances,
        EloquentCollection $userWalletTransactionLogs,
        array $pagination
    )
    {
        /** @var EloquentCollection userBalances */
        $this->userBalances = $userBalances;

        /** @var EloquentCollection userWalletTransactionLogs */
        $this->userWalletTransactionLogs = $userWalletTransactionLogs;

        /** @var array pagination */
        $this->pagination = $pagination;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'user_balances',
        'logs',
        'pagination'
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
    public function includeUserBalances() : ?Collection
    {
        return $this->collection($this->userBalances, new UserBalanceTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeLogs() : ?Collection
    {
        return $this->collection($this->userWalletTransactionLogs, new UserWalletTransactionLogTransformer);
    }

    /**
     * @return Item|null
     */
    public function includePagination() : ?Item
    {
        return $this->item($this->pagination, new WalletPagePaginationTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'wallet_page';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'wallet_pages';
    }
}
