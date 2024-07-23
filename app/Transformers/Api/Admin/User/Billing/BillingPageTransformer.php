<?php

namespace App\Transformers\Api\Admin\User\Billing;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Billing;
use App\Repositories\Billing\BillingChangeRequestRepository;
use App\Repositories\VatNumberProof\VatNumberProofRepository;
use App\Services\Billing\BillingChangeRequestService;
use App\Transformers\Api\Admin\User\Billing\VatNumberProof\VatNumberProofTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class BillingPageTransformer
 *
 * @package App\Transformers\Api\Admin\User\Billing
 */
class BillingPageTransformer extends BaseTransformer
{
    /**
     * @var Billing
     */
    protected Billing $billing;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $vatNumberProofs;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $adminAvatars;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $vatNumberProofImages;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $vatNumberProofDocuments;

    /**
     * @var BillingChangeRequestRepository
     */
    protected BillingChangeRequestRepository $billingChangeRequestRepository;

    /**
     * @var BillingChangeRequestService
     */
    protected BillingChangeRequestService $billingChangeRequestService;

    /**
     * @var VatNumberProofRepository
     */
    protected VatNumberProofRepository $vatNumberProofRepository;

    /**
     * BillingPageTransformer constructor
     *
     * @param Billing $billing
     * @param EloquentCollection|null $vatNumberProofs
     * @param EloquentCollection|null $adminAvatars
     * @param EloquentCollection|null $vatNumberProofImages
     * @param EloquentCollection|null $vatNumberProofDocuments
     */
    public function __construct(
        Billing $billing,
        EloquentCollection $vatNumberProofs = null,
        EloquentCollection $adminAvatars = null,
        EloquentCollection $vatNumberProofImages = null,
        EloquentCollection $vatNumberProofDocuments = null
    )
    {
        /** @var Billing billing */
        $this->billing = $billing;

        /** @var EloquentCollection vatNumberProofs */
        $this->vatNumberProofs = $vatNumberProofs;

        /** @var EloquentCollection adminAvatars */
        $this->adminAvatars = $adminAvatars;

        /** @var EloquentCollection vatNumberProofImages */
        $this->vatNumberProofImages = $vatNumberProofImages;

        /** @var EloquentCollection vatNumberProofDocuments */
        $this->vatNumberProofDocuments = $vatNumberProofDocuments;

        /** @var BillingChangeRequestRepository billingChangeRequestRepository */
        $this->billingChangeRequestRepository = new BillingChangeRequestRepository();

        /** @var BillingChangeRequestService billingChangeRequestService */
        $this->billingChangeRequestService = new BillingChangeRequestService();

        /** @var VatNumberProofRepository vatNumberProofRepository */
        $this->vatNumberProofRepository = new VatNumberProofRepository();
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'user',
        'billing',
        'billing_change_request',
        'vat_number_proofs',
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
    public function includeUser() : ?Item
    {
        $user = null;

        if ($this->billing->relationLoaded('user')) {
            $user = $this->billing->user;
        }

        return $user ? $this->item($user, new UserTransformer) : null;
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
        $billingChangeRequest = $this->billingChangeRequestRepository->findPendingForUser(
            $this->billing->user
        );

        return $billingChangeRequest ? $this->item($billingChangeRequest, new BillingChangeRequestTransformer) : null;
    }

    /**
     * @return Collection|null
     */
    public function includeVatNumberProofs() : ?Collection
    {
        $vatNumberProofs = null;

        if ($this->vatNumberProofs) {
            $vatNumberProofs = $this->vatNumberProofs;
        }

        return $vatNumberProofs ?
            $this->collection(
                $vatNumberProofs,
                new VatNumberProofTransformer(
                    $this->adminAvatars,
                    $this->vatNumberProofImages,
                    $this->vatNumberProofDocuments
                )
            ) : null;
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
