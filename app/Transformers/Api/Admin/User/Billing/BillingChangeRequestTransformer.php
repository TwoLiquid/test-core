<?php

namespace App\Transformers\Api\Admin\User\Billing;

use App\Models\MongoDb\User\Billing\BillingChangeRequest;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class BillingChangeRequestTransformer
 *
 * @package App\Transformers\Api\Admin\User\Billing
 */
class BillingChangeRequestTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'user',
        'country_place',
        'previous_country_place',
        'country_place_status',
        'region_place',
        'admin',
        'request_status',
        'toast_message_type'
    ];

    /**
     * @param BillingChangeRequest $billingChangeRequest
     *
     * @return array
     */
    public function transform(BillingChangeRequest $billingChangeRequest) : array
    {
        return [
            'id'                 => $billingChangeRequest->_id,
            'shown'              => $billingChangeRequest->shown,
            'toast_message_text' => $billingChangeRequest->toast_message_text
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
    public function includePreviousCountryPlace(BillingChangeRequest $billingChangeRequest) : ?Item
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
    public function includeCountryPlaceStatus(BillingChangeRequest $billingChangeRequest) : ?Item
    {
        $countryPlaceStatus = $billingChangeRequest->getCountryPlaceStatus();

        return $countryPlaceStatus ? $this->item($countryPlaceStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param BillingChangeRequest $billingChangeRequest
     *
     * @return Item|null
     */
    public function includeRegionPlace(BillingChangeRequest $billingChangeRequest) : ?Item
    {
        $regionPlace = null;

        if ($billingChangeRequest->relationLoaded('regionPlace')) {
            $regionPlace = $billingChangeRequest->regionPlace;
        }

        return $regionPlace ? $this->item($regionPlace, new RegionPlaceTransformer) : null;
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
    public function includeToastMessageType(BillingChangeRequest $billingChangeRequest) : ?Item
    {
        $toastMessageType = $billingChangeRequest->getToastMessageType();

        return $toastMessageType ? $this->item($toastMessageType, new ToastMessageTypeTransformer) : null;
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
