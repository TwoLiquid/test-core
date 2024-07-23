<?php

namespace App\Transformers\Api\General\Setting\Payout\Method;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Payment\PaymentMethod;
use App\Repositories\Payout\PayoutMethodRequestRepository;
use App\Transformers\Api\General\Setting\Payout\Method\Request\PayoutMethodRequestTransformer;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class PaymentMethodTransformer
 *
 * @package App\Transformers\Api\General\Setting\Payout\Method
 */
class PaymentMethodTransformer extends BaseTransformer
{
    /**
     * @var PayoutMethodRequestRepository
     */
    protected PayoutMethodRequestRepository $payoutMethodRequestRepository;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $paymentMethodImages;

    /**
     * PaymentMethodTransformer constructor
     *
     * @param EloquentCollection|null $paymentMethodImages
     */
    public function __construct(
        EloquentCollection $paymentMethodImages = null
    )
    {
        /** @var PayoutMethodRequestRepository payoutMethodRequestRepository */
        $this->payoutMethodRequestRepository = new PayoutMethodRequestRepository();

        /** @var EloquentCollection paymentMethodImages */
        $this->paymentMethodImages = $paymentMethodImages;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'image',
        'payment_status',
        'withdrawal_status',
        'country_places',
        'excluded_country_places',
        'fields',
        'payout_method_request'
    ];

    /**
     * @param PaymentMethod $paymentMethod
     *
     * @return array
     */
    public function transform(PaymentMethod $paymentMethod) : array
    {
        return [
            'id'              => $paymentMethod->id,
            'code'            => $paymentMethod->code,
            'name'            => $paymentMethod->name,
            'payment_fee'     => $paymentMethod->payment_fee,
            'order_form'      => $paymentMethod->order_form,
            'display_name'    => $paymentMethod->display_name,
            'duration_title'  => $paymentMethod->duration_title,
            'duration_amount' => $paymentMethod->duration_amount,
            'fee_title'       => $paymentMethod->fee_title,
            'fee_amount'      => $paymentMethod->fee_amount
        ];
    }

    /**
     * @param PaymentMethod $paymentMethod
     *
     * @return Item|null
     */
    public function includeImage(PaymentMethod $paymentMethod) : ?Item
    {
        $paymentMethodImage = $this->paymentMethodImages?->filter(function ($item) use ($paymentMethod) {
            return $item->method_id == $paymentMethod->id;
        })->first();

        return $paymentMethodImage ? $this->item($paymentMethodImage, new PaymentMethodImageTransformer) : null;
    }

    /**
     * @param PaymentMethod $paymentMethod
     *
     * @return Item|null
     */
    public function includePaymentStatus(PaymentMethod $paymentMethod) : ?Item
    {
        $paymentStatus = $paymentMethod->getPaymentStatus();

        return $paymentStatus ? $this->item($paymentStatus, new PaymentStatusTransformer) : null;
    }

    /**
     * @param PaymentMethod $paymentMethod
     *
     * @return Item|null
     */
    public function includeWithdrawalStatus(PaymentMethod $paymentMethod) : ?Item
    {
        $withdrawalStatus = $paymentMethod->getWithdrawalStatus();

        return $withdrawalStatus ? $this->item($withdrawalStatus, new PaymentMethodWithdrawalStatusTransformer) : null;
    }

    /**
     * @param PaymentMethod $paymentMethod
     *
     * @return Collection|null
     */
    public function includeCountryPlaces(PaymentMethod $paymentMethod) : ?Collection
    {
        $countryPlaces = null;

        if ($paymentMethod->relationLoaded('countryPlaces')) {
            $countryPlaces = $paymentMethod->countryPlaces;
        }

        return $countryPlaces ? $this->collection($countryPlaces, new CountryPlaceTransformer) : null;
    }

    /**
     * @param PaymentMethod $paymentMethod
     *
     * @return Collection|null
     */
    public function includeExcludedCountryPlaces(PaymentMethod $paymentMethod) : ?Collection
    {
        $excludedCountryPlaces = null;

        if ($paymentMethod->relationLoaded('excludedCountryPlaces')) {
            $excludedCountryPlaces = $paymentMethod->excludedCountryPlaces;
        }

        return $excludedCountryPlaces ? $this->collection($excludedCountryPlaces, new CountryPlaceTransformer) : null;
    }

    /**
     * @param PaymentMethod $paymentMethod
     *
     * @return Collection|null
     */
    public function includeFields(PaymentMethod $paymentMethod) : ?Collection
    {
        $fields = null;

        if ($paymentMethod->relationLoaded('fields')) {
            $fields = $paymentMethod->fields;
        }

        return $fields ? $this->collection($fields, new PaymentMethodFieldTransformer) : null;
    }

    /**
     * @param PaymentMethod $paymentMethod
     *
     * @return Item|null
     *
     * @throws DatabaseException
     */
    public function includePayoutMethodRequest(PaymentMethod $paymentMethod) : ?Item
    {
        $payoutMethodRequest = $this->payoutMethodRequestRepository->findLast(
            $paymentMethod
        );

        return $payoutMethodRequest ? $this->item($payoutMethodRequest, new PayoutMethodRequestTransformer(
            $this->paymentMethodImages
        )) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'payment_method';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'payment_methods';
    }
}
