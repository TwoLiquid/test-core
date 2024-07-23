<?php

namespace App\Transformers\Api\General\Setting\Billing;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Billing;
use App\Repositories\Billing\BillingChangeRequestRepository;
use App\Services\Billing\BillingChangeRequestService;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class BillingPageTransformer
 *
 * @package App\Transformers\Api\General\Setting\Billing
 */
class BillingPageTransformer extends BaseTransformer
{
    /**
     * @var Billing
     */
    protected Billing $billing;

    /**
     * @var BillingChangeRequestRepository
     */
    protected BillingChangeRequestRepository $billingChangeRequestRepository;

    /**
     * @var BillingChangeRequestService
     */
    protected BillingChangeRequestService $billingChangeRequestService;

    /**
     * BillingPageTransformer constructor
     *
     * @param Billing $billing
     */
    public function __construct(
        Billing $billing
    )
    {
        /** @var Billing billing */
        $this->billing = $billing;

        /** @var BillingChangeRequestRepository billingChangeRequestRepository */
        $this->billingChangeRequestRepository = new BillingChangeRequestRepository();

        /** @var BillingChangeRequestService billingChangeRequestService */
        $this->billingChangeRequestService = new BillingChangeRequestService();
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'billing',
        'billing_change_request',
        'form'
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
    public function includeBilling() : ?Item
    {
        $billing = $this->billing;

        return $this->item($billing, new BillingTransformer);
    }

    /**
     * @return Item|null
     *
     * @throws DatabaseException
     */
    public function includeBillingChangeRequest() : ?Item
    {
        $billingChangeRequest = $this->billingChangeRequestRepository->findLastForUser(
            $this->billing->user
        );

        if ($billingChangeRequest) {
            if ($billingChangeRequest->getRequestStatus()->isAccepted() ||
                $billingChangeRequest->getRequestStatus()->isCanceled()
            ) {
                $billingChangeRequest = null;
            } elseif ($billingChangeRequest->getRequestStatus()->isDeclined()) {
                if ($billingChangeRequest->shown === false) {
                    $this->billingChangeRequestRepository->updateShown(
                        $billingChangeRequest,
                        true
                    );
                } else {
                    $billingChangeRequest = null;
                }
            }
        }

        return $billingChangeRequest ? $this->item($billingChangeRequest, new BillingChangeRequestTransformer) : null;
    }

    /**
     * @return Item|null
     */
    public function includeForm() : ?Item
    {
        return $this->item([], new FormTransformer($this->billing));
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'billing_page';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'billing_pages';
    }
}
