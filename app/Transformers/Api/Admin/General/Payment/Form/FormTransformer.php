<?php

namespace App\Transformers\Api\Admin\General\Payment\Form;

use App\Exceptions\DatabaseException;
use App\Lists\Language\LanguageList;
use App\Lists\Payment\Method\Field\Type\PaymentMethodFieldTypeList;
use App\Lists\Payment\Method\Payment\Status\PaymentStatusList;
use App\Lists\Withdrawal\Receipt\Status\WithdrawalReceiptStatusList;
use App\Repositories\Place\CountryPlaceRepository;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;

/**
 * Class FormTransformer
 *
 * @package App\Transformers\Api\Admin\General\Payment\Form
 */
class FormTransformer extends BaseTransformer
{
    /**
     * @var CountryPlaceRepository
     */
    protected CountryPlaceRepository $countryPlaceRepository;

    /**
     * FormTransformer constructor
     */
    public function __construct()
    {
        /** @var CountryPlaceRepository countryPlaceRepository */
        $this->countryPlaceRepository = new CountryPlaceRepository();
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'country_places',
        'languages',
        'field_types',
        'payment_statuses',
        'withdrawal_receipt_statuses'
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
    public function includeCountryPlaces() : ?Collection
    {
        $countryPlaces = $this->countryPlaceRepository->getAllWithoutExcluded();

        return $this->collection($countryPlaces, new CountryPlaceTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeLanguages() : ?Collection
    {
        $languages = LanguageList::getTranslatableItems();

        return $this->collection($languages, new LanguageTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeFieldTypes() : ?Collection
    {
        $paymentMethodFieldTypes = PaymentMethodFieldTypeList::getItems();

        return $this->collection($paymentMethodFieldTypes, new PaymentMethodFieldTypeTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includePaymentStatuses() : ?Collection
    {
        $paymentStatuses = PaymentStatusList::getItems();

        return $this->collection($paymentStatuses, new PaymentStatusTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeWithdrawalReceiptStatuses() : ?Collection
    {
        $withdrawalReceiptStatuses = WithdrawalReceiptStatusList::getItems();

        return $this->collection($withdrawalReceiptStatuses, new WithdrawalReceiptStatusTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'form';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'forms';
    }
}
