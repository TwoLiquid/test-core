<?php

namespace App\Services\Payout;

use App\Exceptions\DatabaseException;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Lists\User\Balance\Type\UserBalanceTypeList;
use App\Lists\User\Balance\Type\UserBalanceTypeListItem;
use App\Models\MongoDb\Payout\PayoutMethodRequest;
use App\Repositories\Payment\PaymentMethodFieldRepository;
use App\Repositories\Payout\PayoutMethodRequestFieldRepository;
use App\Repositories\Payout\PayoutMethodRequestRepository;
use App\Repositories\User\UserRepository;
use App\Services\Payout\Interfaces\PayoutMethodRequestServiceInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class PayoutMethodRequestService
 *
 * @package App\Services\Payout
 */
class PayoutMethodRequestService implements PayoutMethodRequestServiceInterface
{
    /**
     * @var PaymentMethodFieldRepository
     */
    protected PaymentMethodFieldRepository $paymentMethodFieldRepository;

    /**
     * @var PayoutMethodRequestRepository
     */
    protected PayoutMethodRequestRepository $payoutMethodRequestRepository;

    /**
     * @var PayoutMethodRequestFieldRepository
     */
    protected PayoutMethodRequestFieldRepository $payoutMethodRequestFieldRepository;

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * PayoutMethodRequestService constructor
     */
    public function __construct()
    {
        /** @var PaymentMethodFieldRepository paymentMethodFieldRepository */
        $this->paymentMethodFieldRepository = new PaymentMethodFieldRepository();

        /** @var PayoutMethodRequestRepository payoutMethodRequestRepository */
        $this->payoutMethodRequestRepository = new PayoutMethodRequestRepository();

        /** @var PayoutMethodRequestFieldRepository payoutMethodRequestFieldRepository */
        $this->payoutMethodRequestFieldRepository = new PayoutMethodRequestFieldRepository();

        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();
    }

    /**
     * @param PayoutMethodRequest $payoutMethodRequest
     * @param array $fields
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function createFields(
        PayoutMethodRequest $payoutMethodRequest,
        array $fields
    ) : Collection
    {
        /**
         * Preparing payout method request variable
         */
        $payoutMethodRequestFields = new Collection();

        /** @var array $field */
        foreach ($fields as $field) {

            /**
             * Getting payment method field
             */
            $paymentMethodField = $this->paymentMethodFieldRepository->findById(
                $field['id']
            );

            /**
             * Creating payout method request field
             */
            $payoutMethodRequestField = $this->payoutMethodRequestFieldRepository->store(
                $payoutMethodRequest,
                $paymentMethodField,
                $field['value']
            );

            /**
             * Adding payout method request field to a collection
             */
            $payoutMethodRequestFields->push(
                $payoutMethodRequestField
            );
        }

        return $payoutMethodRequestFields;
    }

    /**
     * @param PayoutMethodRequest $payoutMethodRequest
     *
     * @throws DatabaseException
     */
    public function executeRequest(
        PayoutMethodRequest $payoutMethodRequest
    ) : void
    {
        /**
         * Getting user
         */
        $user = $payoutMethodRequest->user;

        /**
         * Getting payment method
         */
        $paymentMethod = $payoutMethodRequest->method;

        /**
         * Attaching payout method
         */
        $this->userRepository->attachPayoutMethod(
            $user,
            $paymentMethod
        );

        foreach ($payoutMethodRequest->fields as $payoutMethodRequestField) {
            $paymentMethodField = $payoutMethodRequestField->field;

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
                $payoutMethodRequestField->value
            );
        }
    }

    /**
     * @param Collection|null $payoutMethodRequests
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllStatusesWithCounts(
        ?Collection $payoutMethodRequests
    ) : Collection
    {
        $requestStatuses = new Collection();

        /** @var RequestStatusListItem $requestStatusListItem */
        foreach (RequestStatusList::getItems() as $requestStatusListItem) {

            /**
             * Checking payout method requests existence
             */
            if ($payoutMethodRequests) {

                /**
                 * Getting request status count
                 */
                $count = $this->payoutMethodRequestRepository->getRequestStatusCountByIds(
                    $payoutMethodRequests->pluck('_id')
                        ->values()
                        ->toArray(),
                    $requestStatusListItem
                );
            } else {

                /**
                 * Getting request status count
                 */
                $count = $this->payoutMethodRequestRepository->getRequestStatusCount(
                    $requestStatusListItem
                );
            }

            /**
             * Setting count
             */
            $requestStatusListItem->setCount($count);

            /**
             * Adding request status to a response collection
             */
            $requestStatuses->add($requestStatusListItem);
        }

        return $requestStatuses;
    }

    /**
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getUserBalanceTypesWithCounts() : Collection
    {
        /**
         * Getting a user balance types
         */
        $userBalanceTypeListItems = UserBalanceTypeList::getItems();

        /** @var UserBalanceTypeListItem $userBalanceTypeListItem */
        foreach ($userBalanceTypeListItems as $userBalanceTypeListItem) {

            /**
             * Setting buyers payment method requests count
             */
            if ($userBalanceTypeListItem->isBuyer()) {
                $userBalanceTypeListItem->setCount(
                    $this->payoutMethodRequestRepository->getAllBuyersCount()
                );
            }

            /**
             * Setting sellers payment method  requests count
             */
            if ($userBalanceTypeListItem->isSeller()) {
                $userBalanceTypeListItem->setCount(
                    $this->payoutMethodRequestRepository->getAllSellersCount()
                );
            }

            /**
             * Setting affiliates payment method requests count
             */
            if ($userBalanceTypeListItem->isAffiliate()) {
                $userBalanceTypeListItem->setCount(
                    $this->payoutMethodRequestRepository->getAllAffiliatesCount()
                );
            }
        }

        return $userBalanceTypeListItems;
    }
}
