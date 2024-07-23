<?php

namespace App\Transformers\Api\General\Dashboard\Wallet\Withdrawal;

use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class WithdrawalTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Wallet\Withdrawal
 */
class WithdrawalTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $payoutMethods;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $withdrawalRequests;

    /**
     * WithdrawalTransformer constructor
     *
     * @param EloquentCollection $payoutMethods
     * @param EloquentCollection $withdrawalRequests
     */
    public function __construct(
        EloquentCollection $payoutMethods,
        EloquentCollection $withdrawalRequests
    )
    {
        $this->payoutMethods = $payoutMethods;
        $this->withdrawalRequests = $withdrawalRequests;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'payment_methods',
        'withdrawal_requests'
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
    public function includePaymentMethods() : ?Collection
    {
        return $this->collection($this->payoutMethods, new PaymentMethodTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeWithdrawalRequests() : ?Collection
    {
        return $this->collection($this->withdrawalRequests, new WithdrawalRequestTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'withdrawal';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'withdrawals';
    }
}
