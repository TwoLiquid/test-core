<?php

namespace App\Transformers\Api\Admin\Request\Finance\BillingChangeRequest;

use App\Models\MongoDb\User\Billing\BillingChangeRequest;
use App\Transformers\BaseTransformer;
use Carbon\Carbon;
use League\Fractal\Resource\Item;

/**
 * Class BillingChangeRequestTransformer
 *
 * @package App\Transformers\Api\Admin\Request\Finance\BillingChangeRequest
 */
class BillingChangeRequestTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'user',
        'country_place',
        'old_country_place',
        'language',
        'request_status',
        'admin'
    ];

    /**
     * @param BillingChangeRequest $billingChangeRequest
     *
     * @return array
     */
    public function transform(BillingChangeRequest $billingChangeRequest) : array
    {
        return [
            'id'         => $billingChangeRequest->_id,
            'waiting'    => Carbon::now()->diff($billingChangeRequest->created_at)->format('%H:%I:%S'),
            'created_at' => $billingChangeRequest->created_at->format('Y-m-d\TH:i:s.v\Z')
        ];
    }

    /**
     * @param BillingChangeRequest $billingChangeRequest
     *
     * @return Item|null
     */
    public function includeUser(BillingChangeRequest $billingChangeRequest) : ?Item
    {
        $user = null;

        if ($billingChangeRequest->relationLoaded('user')) {
            $user = $billingChangeRequest->user;
        }

        return $user ? $this->item($user, new UserTransformer) : null;
    }

    /**
     * @param BillingChangeRequest $billingChangeRequest
     *
     * @return Item|null
     */
    public function includeCountryPlace(BillingChangeRequest $billingChangeRequest) : ?Item
    {
        $countryPlace = null;

        if ($billingChangeRequest->relationLoaded('countryPlace')) {
            $countryPlace = $billingChangeRequest->countryPlace;
        }

        return $countryPlace ? $this->item($countryPlace, new CountryPlaceTransformer) : null;
    }

    /**
     * @param BillingChangeRequest $billingChangeRequest
     *
     * @return Item|null
     */
    public function includeOldCountryPlace(BillingChangeRequest $billingChangeRequest) : ?Item
    {
        $previousCountryPlace = null;

        if ($billingChangeRequest->relationLoaded('previousCountryPlace')) {
            $previousCountryPlace = $billingChangeRequest->previousCountryPlace;
        }


        return $previousCountryPlace ? $this->item($previousCountryPlace, new CountryPlaceTransformer) : null;
    }

    /**
     * @param BillingChangeRequest $billingChangeRequest
     *
     * @return Item|null
     */
    public function includeAdmin(BillingChangeRequest $billingChangeRequest) : ?Item
    {
        $admin = null;

        if ($billingChangeRequest->relationLoaded('admin')) {
            $admin = $billingChangeRequest->admin;
        }

        return $admin ? $this->item($admin, new AdminTransformer) : null;
    }

    /**
     * @param BillingChangeRequest $billingChangeRequest
     *
     * @return Item|null
     */
    public function includeLanguage(BillingChangeRequest $billingChangeRequest) : ?Item
    {
        $language = $billingChangeRequest->getLanguage();

        return $language ? $this->item($language, new LanguageTransformer) : null;
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
