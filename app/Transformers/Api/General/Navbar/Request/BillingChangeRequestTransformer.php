<?php

namespace App\Transformers\Api\General\Navbar\Request;

use App\Models\MongoDb\User\Billing\BillingChangeRequest;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class BillingChangeRequestTransformer
 *
 * @package App\Transformers\Api\General\Navbar\Request
 */
class BillingChangeRequestTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'request_status'
    ];

    /**
     * @param BillingChangeRequest $billingChangeRequest
     *
     * @return array
     */
    public function transform(BillingChangeRequest $billingChangeRequest) : array
    {
        return [
            'id' => $billingChangeRequest->_id
        ];
    }

    /**
     * @param BillingChangeRequest $billingChangeRequest
     *
     * @return Item|null
     */
    public function includeRequestStatus(BillingChangeRequest $billingChangeRequest) : ?Item
    {
        $requestStatus = $billingChangeRequest->getRequestStatus();

        return $requestStatus ? $this->item($requestStatus, new RequestStatusTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'billing_change_request';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'billing_change_requests';
    }
}
