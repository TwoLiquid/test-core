<?php

namespace App\Transformers\Api\Admin\Request\Finance\BillingChangeRequest;

use App\Exceptions\DatabaseException;
use App\Services\Billing\BillingChangeRequestService;
use App\Services\Request\RequestService;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class BillingChangeRequestListPageFilterTransformer
 *
 * @package App\Transformers\Api\Admin\Request\Finance\BillingChangeRequest
 */
class BillingChangeRequestListPageFilterTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $requestStatuses;

    /**
     * @var BillingChangeRequestService
     */
    protected BillingChangeRequestService $billingChangeRequestService;

    /**
     * @var RequestService
     */
    protected RequestService $requestService;

    /**
     * BillingChangeRequestListPageFilterTransformer constructor
     *
     * @param EloquentCollection $requestStatuses
     */
    public function __construct(
        EloquentCollection $requestStatuses
    )
    {
        /** @var EloquentCollection requestStatuses */
        $this->requestStatuses = $requestStatuses;

        /** @var BillingChangeRequestService billingChangeRequestService */
        $this->billingChangeRequestService = new BillingChangeRequestService();

        /** @var RequestService requestService */
        $this->requestService = new RequestService();
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'user_balance_types',
        'request_groups',
        'request_types',
        'request_statuses'
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
     *
     * @throws DatabaseException
     */
    public function includeUserBalanceTypes() : ?Collection
    {
        $userBalanceTypes = $this->billingChangeRequestService->getUserBalanceTypesWithCounts();

        return $this->collection($userBalanceTypes, new UserBalanceTypeTransformer);
    }

    /**
     * @return Collection|null
     *
     * @throws DatabaseException
     */
    public function includeRequestGroups() : ?Collection
    {
        $requestGroups = $this->requestService->getRequestGroupsWithCounts();

        return $this->collection($requestGroups, new RequestGroupTransformer);
    }

    /**
     * @return Collection|null
     *
     * @throws DatabaseException
     */
    public function includeRequestTypes() : ?Collection
    {
        $requestTypes = $this->requestService->getFinanceRequestsCounts();

        return $this->collection($requestTypes, new RequestTypeTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeRequestStatuses() : ?Collection
    {
        $requestStatuses = $this->requestStatuses;

        return $this->collection($requestStatuses, new RequestStatusTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'filters';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'filters';
    }
}
