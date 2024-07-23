<?php

namespace App\Services\Payment;

use App\Exceptions\DatabaseException;
use App\Exceptions\ValidationException;
use App\Lists\Payment\Method\Field\Type\PaymentMethodFieldTypeList;
use App\Models\MongoDb\Payout\PayoutMethodRequest;
use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\Payment\PaymentMethodField;
use App\Models\MySql\Place\CountryPlace;
use App\Models\MySql\User\User;
use App\Repositories\Payment\PaymentMethodFieldRepository;
use App\Repositories\Payment\PaymentMethodRepository;
use App\Repositories\Payout\PayoutMethodRequestRepository;
use App\Services\Payment\Interfaces\PaymentMethodServiceInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class PaymentMethodService
 *
 * @package App\Services\Payment
 */
class PaymentMethodService implements PaymentMethodServiceInterface
{
    /**
     * @var PaymentMethodFieldRepository
     */
    protected PaymentMethodFieldRepository $paymentMethodFieldRepository;

    /**
     * @var PaymentMethodRepository
     */
    protected PaymentMethodRepository $paymentMethodRepository;

    /**
     * @var PayoutMethodRequestRepository
     */
    protected PayoutMethodRequestRepository $payoutMethodRequestRepository;

    /**
     * PaymentMethodService constructors
     */
    public function __construct()
    {
        /** @var PaymentMethodFieldRepository paymentMethodFieldRepository */
        $this->paymentMethodFieldRepository = new PaymentMethodFieldRepository();

        /** @var PaymentMethodRepository paymentMethodRepository */
        $this->paymentMethodRepository = new PaymentMethodRepository();

        /** @var PayoutMethodRequestRepository payoutMethodRequestRepository */
        $this->payoutMethodRequestRepository = new PayoutMethodRequestRepository();
    }

    /**
     * @param PaymentMethod $paymentMethod
     *
     * @return bool
     */
    public function isBalance(
        PaymentMethod $paymentMethod
    ) : bool
    {
        /**
         * Checking payment method
         */
        if ($paymentMethod->code == 'balance') {
            return true;
        }

        return false;
    }

    /**
     * @param PaymentMethod $paymentMethod
     * @param array $fields
     *
     * @return bool
     *
     * @throws DatabaseException
     * @throws ValidationException
     */
    public function validateFields(
        PaymentMethod $paymentMethod,
        array $fields
    ) : bool
    {
        $paymentMethodFieldsIds = $paymentMethod->fields
            ->pluck('id')
            ->values()
            ->toArray();

        /**
         * @var int $key
         * @var array $field
         */
        foreach ($fields as $key => $field) {
            $paymentMethodFieldsIds = array_diff($paymentMethodFieldsIds, [$field['id']]);
        }

        /**
         * Checking all payment method fields are presented
         */
        if (!empty($paymentMethodFieldsIds)) {
            throw new ValidationException(
                trans('exceptions/service/payout/payoutMethod.' . __FUNCTION__ . '.match'),
                'fields.' . $key . '.id'
            );
        }

        /**
         * @var int $key
         * @var array $field
         */
        foreach ($fields as $key => $field) {

            /**
             * Getting payment method field
             */
            $paymentMethodField = $this->paymentMethodFieldRepository->findById(
                $field['id']
            );

            /**
             * Comparing field method
             */
            if (!$paymentMethod->is($paymentMethodField->method)) {
                throw new ValidationException(
                    trans('exceptions/service/payout/payoutMethod.' . __FUNCTION__ . '.method'),
                    'fields.' . $key . '.id'
                );
            }

            /**
             * Checking a value string type
             */
            if ($paymentMethodField->getType()->isString()) {
                if (!is_string($field['value'])) {
                    throw new ValidationException(
                        trans('exceptions/service/payout/payoutMethod.' . __FUNCTION__ . '.string'),
                        'fields.' . $key . '.value'
                    );
                }
            }

            /**
             * Checking value integer type
             */
            if ($paymentMethodField->getType()->isInteger()) {
                if (!is_int($field['value'])) {
                    throw new ValidationException(
                        trans('exceptions/service/payout/payoutMethod.' . __FUNCTION__ . '.integer'),
                        'fields.' . $key . '.value'
                    );
                }
            }

            /**
             * Checking a value boolean type
             */
            if ($paymentMethodField->getType()->isBoolean()) {
                if (!is_bool($field['value'])) {
                    throw new ValidationException(
                        trans('exceptions/service/payout/payoutMethod.' . __FUNCTION__ . '.boolean'),
                        'fields.' . $key . '.value'
                    );
                }
            }
        }

        return true;
    }

    /**
     * @param PaymentMethod $paymentMethod
     * @param array $fields
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function createFields(
        PaymentMethod $paymentMethod,
        array $fields
    ) : Collection
    {
        /**
         * Preparing payment method fields variable
         */
        $paymentMethodFields = new Collection();

        /** @var array $field */
        foreach ($fields as $field) {

            /**
             * Getting a payment method field type
             */
            $paymentMethodFieldTypeListItem = PaymentMethodFieldTypeList::getItem(
                $field['type_id']
            );

            /**
             * Creating payment method field
             */
            $paymentMethodField = $this->paymentMethodFieldRepository->store(
                $paymentMethod,
                $paymentMethodFieldTypeListItem,
                $field['title'],
                $field['placeholder'],
                $field['tooltip'] ?? null
            );

            /**
             * Adding payment method field to a collection
             */
            $paymentMethodFields->push(
                $paymentMethodField
            );
        }

        return $paymentMethodFields;
    }

    /**
     * @param PaymentMethod $paymentMethod
     * @param array $fields
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function updateFields(
        PaymentMethod $paymentMethod,
        array $fields
    ) : Collection
    {
        /**
         * Preparing payment method fields variable
         */
        $paymentMethodFields = new Collection();

        $updatePaymentMethodIds = [];

        /** @var array $field */
        foreach ($fields as $field) {
            if (isset($field['id'])) {
                $updatePaymentMethodIds[] = $field['id'];
            }
        }

        /** @var PaymentMethodField $paymentMethodField */
        foreach ($paymentMethod->fields as $paymentMethodField) {
            if (!in_array($paymentMethodField->id, $updatePaymentMethodIds)) {
                $this->paymentMethodFieldRepository->delete(
                    $paymentMethodField
                );
            }
        }

        /** @var array $field */
        foreach ($fields as $field) {
            $paymentMethodFieldTypeListItem = PaymentMethodFieldTypeList::getItem(
                $field['type_id']
            );

            if (isset($field['id'])) {

                /** @var PaymentMethodField $paymentMethodField */
                $paymentMethodField = $paymentMethod->fields->find(
                    $field['id']
                );

                $paymentMethodFields->push(
                    $this->paymentMethodFieldRepository->update(
                    $paymentMethodField,
                    $paymentMethod,
                        $paymentMethodFieldTypeListItem,
                    $field['title'],
                    $field['placeholder'],
                        $field['tooltip'] ?? null
                ));
            } else {
                $paymentMethodFields->push(
                    $this->paymentMethodFieldRepository->store(
                    $paymentMethod,
                        $paymentMethodFieldTypeListItem,
                    $field['title'],
                    $field['placeholder'],
                    $field['tooltip'] ?? null
                ));
            }
        }

        return $paymentMethodFields;
    }

    /**
     * @param User $user
     * @param array $fields
     *
     * @throws DatabaseException
     */
    public function updateFieldsUserValues(
        User $user,
        array $fields
    ) : void
    {
        /** @var array $field */
        foreach ($fields as $field) {

            /**
             * Getting payment method
             */
            $paymentMethodField = $this->paymentMethodFieldRepository->findById(
                $field['id']
            );

            /**
             * Detaching user from payment method field
             */
            $this->paymentMethodFieldRepository->detachUser(
                $paymentMethodField,
                $user
            );

            /**
             * Attaching user with value to payment method field
             */
            $this->paymentMethodFieldRepository->attachUser(
                $paymentMethodField,
                $user,
                $field['value']
            );
        }
    }

    /**
     * @param Collection $payoutMethodRequests
     *
     * @return Collection
     */
    public function getByPayoutMethodRequests(
        Collection $payoutMethodRequests
    ) : Collection
    {
        $paymentMethods = new Collection();

        /** @var PayoutMethodRequest $payoutMethodRequest */
        foreach ($payoutMethodRequests as $payoutMethodRequest) {
            if ($payoutMethodRequest->relationLoaded('method')) {
                $paymentMethods->push(
                    $payoutMethodRequest->method
                );
            }
        }

        return $paymentMethods;
    }

    /**
     * @param PaymentMethod $paymentMethod
     * @param Collection $countryPlaces
     * @param bool $excluded
     *
     * @return void
     *
     * @throws DatabaseException
     */
    public function updateCountryPlaces(
        PaymentMethod $paymentMethod,
        Collection $countryPlaces,
        bool $excluded = false
    ) : void
    {
        /** @var CountryPlace $countryPlace */
        foreach ($countryPlaces as $countryPlace) {

            /**
             * Attaching country place to payment method
             */
            $this->paymentMethodRepository->detachCountryPlace(
                $paymentMethod,
                $countryPlace
            );
        }

        /** @var CountryPlace $countryPlace */
        foreach ($countryPlaces as $countryPlace) {

            /**
             * Attaching excluded country place to payment method
             */
            $this->paymentMethodRepository->attachCountryPlace(
                $paymentMethod,
                $countryPlace,
                $excluded
            );
        }
    }

    /**
     * @param Collection $paymentMethods
     * @param User $user
     * @param bool $admin
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getForUser(
        Collection $paymentMethods,
        User $user,
        bool $admin = false
    ) : Collection
    {
        /**
         * Getting payment methods
         */
        $userPaymentMethods = $this->paymentMethodRepository->getForUser(
            $user
        );

        /** @var PaymentMethod $paymentMethod */
        foreach ($paymentMethods as $paymentMethod) {

            /**
             * Checking payment method existence
             */
            if ($userPaymentMethods->contains('id', '=', $paymentMethod->id) === false) {

                /**
                 * Getting payout method request
                 */
                $payoutMethodRequest = $this->payoutMethodRequestRepository->findLastForUser(
                    $paymentMethod,
                    $user
                );

                /**
                 * Checking payout method request existence
                 */
                if ($payoutMethodRequest) {

                    /**
                     * Checking payout method request status
                     */
                    if ($payoutMethodRequest->getRequestStatus()->isPending() ||
                        $payoutMethodRequest->getRequestStatus()->isDeclined()
                    ) {

                        /**
                         * Checking admin flag
                         */
                        if ($admin === true) {

                            /**
                             * Adding payment method to a collection
                             */
                            $userPaymentMethods->add(
                                $paymentMethod
                            );
                        } else {

                            /**
                             * Checking payout method request shown
                             */
                            if (!$payoutMethodRequest->shown) {

                                /**
                                 * Adding payment method to a collection
                                 */
                                $userPaymentMethods->add(
                                    $paymentMethod
                                );
                            }
                        }
                    }
                }
            }
        }

        return $userPaymentMethods;
    }
}
