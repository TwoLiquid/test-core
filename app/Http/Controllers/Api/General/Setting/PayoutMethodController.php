<?php

namespace App\Http\Controllers\Api\General\Setting;

use App\Exceptions\DatabaseException;
use App\Exceptions\ValidationException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\General\Setting\Interfaces\PayoutMethodControllerInterface;
use App\Http\Requests\Api\General\Setting\PayoutMethod\StoreRequest;
use App\Http\Requests\Api\General\Setting\PayoutMethod\UpdateRequest;
use App\Lists\Request\Status\RequestStatusList;
use App\Repositories\Media\PaymentMethodImageRepository;
use App\Repositories\Payment\PaymentMethodRepository;
use App\Repositories\Payout\PayoutMethodRequestRepository;
use App\Repositories\User\UserRepository;
use App\Services\Auth\AuthService;
use App\Services\Payment\PaymentMethodService;
use App\Services\Payout\PayoutMethodRequestService;
use App\Transformers\Api\General\Setting\Payout\Method\PayoutMethodListTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class PayoutMethodController
 *
 * @package App\Http\Controllers\Api\General\Setting
 */
class PayoutMethodController extends BaseController implements PayoutMethodControllerInterface
{
    /**
     * @var PaymentMethodRepository
     */
    protected PaymentMethodRepository $paymentMethodRepository;

    /**
     * @var PaymentMethodService
     */
    protected PaymentMethodService $paymentMethodService;

    /**
     * @var PaymentMethodImageRepository
     */
    protected PaymentMethodImageRepository $paymentMethodImageRepository;

    /**
     * @var PayoutMethodRequestRepository
     */
    protected PayoutMethodRequestRepository $payoutMethodRequestRepository;

    /**
     * @var PayoutMethodRequestService
     */
    protected PayoutMethodRequestService $payoutMethodRequestService;

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * PayoutMethodController constructor
     */
    public function __construct()
    {
        /** @var PaymentMethodRepository paymentMethodRepository */
        $this->paymentMethodRepository = new PaymentMethodRepository();

        /** @var PaymentMethodService paymentMethodService */
        $this->paymentMethodService = new PaymentMethodService();

        /** @var PaymentMethodImageRepository paymentMethodImageRepository */
        $this->paymentMethodImageRepository = new PaymentMethodImageRepository();

        /** @var PayoutMethodRequestRepository payoutMethodRequestRepository */
        $this->payoutMethodRequestRepository = new PayoutMethodRequestRepository();

        /** @var PayoutMethodRequestService payoutMethodRequestService */
        $this->payoutMethodRequestService = new PayoutMethodRequestService();

        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();
    }

    /**
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function index() : JsonResponse
    {
        /**
         * Getting payment methods with withdrawal status
         */
        $paymentMethods = $this->paymentMethodRepository->getAllWithdrawalIntegrated();

        /**
         * Getting user payout methods
         */
        $userPaymentMethods = $this->paymentMethodService->getForUser(
            $paymentMethods,
            AuthService::user()
        );

        return $this->respondWithSuccess(
            $this->transformItem([], new PayoutMethodListTransformer(
                $paymentMethods,
                $userPaymentMethods,
                $this->paymentMethodImageRepository->getByPaymentMethods(
                    $paymentMethods
                )
            ))['payment_method_list'],
            trans('validations/api/general/setting/payout/method/index.result.success')
        );
    }

    /**
     * @param StoreRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws ValidationException
     */
    public function store(
        StoreRequest $request
    ) : JsonResponse
    {
        /**
         * Getting payment method
         */
        $paymentMethod = $this->paymentMethodRepository->findFullById(
            $request->input('method_id')
        );

        /**
         * Checking payment method existence
         */
        if (!$paymentMethod) {
            return $this->respondWithError(
                trans('validations/api/general/setting/payout/method/store.result.error.find')
            );
        }

        /**
         * Checking payment method status
         */
        if (!$paymentMethod->getWithdrawalStatus()->isActive()) {
            return $this->respondWithError(
                trans('validations/api/general/setting/payout/method/store.result.error.status')
            );
        }

        /**
         * Checking payment method for user existence
         */
        if ($this->paymentMethodRepository->existsForUser(
            $paymentMethod,
            AuthService::user()
        )) {
            return $this->respondWithError(
                trans('validations/api/general/setting/payout/method/store.result.error.exists')
            );
        }

        /**
         * Getting pending payout method request
         */
        if ($this->payoutMethodRequestRepository->findPendingForUser(
            $paymentMethod,
            AuthService::user()
        )) {
            return $this->respondWithError(
                trans('validations/api/general/setting/payout/method/update.result.error.pending')
            );
        }

        /**
         * Validating fields
         */
        $this->paymentMethodService->validateFields(
            $paymentMethod,
            $request->input('fields')
        );

        /**
         * Creating payout method request
         */
        $payoutMethodRequest = $this->payoutMethodRequestRepository->store(
            $paymentMethod,
            AuthService::user()
        );

        /**
         * Creating payout method request existence
         */
        if (!$payoutMethodRequest) {
            return $this->respondWithError(
                trans('validations/api/general/setting/payout/method/store.result.error.create')
            );
        }

        /**
         * Creating payout method request fields
         */
        $this->payoutMethodRequestService->createFields(
            $payoutMethodRequest,
            $request->input('fields')
        );

        /**
         * Getting payment methods with withdrawal status
         */
        $paymentMethods = $this->paymentMethodRepository->getAllWithdrawalIntegrated();

        /**
         * Getting user payout methods
         */
        $userPaymentMethods = $this->paymentMethodService->getForUser(
            $paymentMethods,
            AuthService::user()
        );

        return $this->respondWithSuccess(
            $this->transformItem([], new PayoutMethodListTransformer(
                $paymentMethods,
                $userPaymentMethods,
                $this->paymentMethodImageRepository->getByPaymentMethods(
                    $paymentMethods
                )
            ))['payment_method_list'],
            trans('validations/api/general/setting/payout/method/store.result.success')
        );
    }

    /**
     * @param int $id
     * @param UpdateRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws ValidationException
     */
    public function update(
        int $id,
        UpdateRequest $request
    ) : JsonResponse
    {
        /**
         * Getting payment method
         */
        $paymentMethod = $this->paymentMethodRepository->findFullById($id);

        /**
         * Checking payment method existence
         */
        if (!$paymentMethod) {
            return $this->respondWithError(
                trans('validations/api/general/setting/payout/method/update.result.error.find')
            );
        }

        /**
         * Checking payment method status
         */
        if (!$paymentMethod->getWithdrawalStatus()->isActive()) {
            return $this->respondWithError(
                trans('validations/api/general/setting/payout/method/update.result.error.status')
            );
        }

        /**
         * Checking payment method for user existence
         */
        if (!$this->paymentMethodRepository->existsForUser(
            $paymentMethod,
            AuthService::user()
        )) {
            return $this->respondWithError(
                trans('validations/api/general/setting/payout/method/update.result.error.exists')
            );
        }

        /**
         * Getting pending payout method request
         */
        if ($this->payoutMethodRequestRepository->findPendingForUser(
            $paymentMethod,
            AuthService::user()
        )) {
            return $this->respondWithError(
                trans('validations/api/general/setting/payout/method/update.result.error.pending')
            );
        }

        /**
         * Validating fields
         */
        $this->paymentMethodService->validateFields(
            $paymentMethod,
            $request->input('fields')
        );

        /**
         * Creating payout method request
         */
        $payoutMethodRequest = $this->payoutMethodRequestRepository->store(
            $paymentMethod,
            AuthService::user()
        );

        /**
         * Creating payout method request existence
         */
        if (!$payoutMethodRequest) {
            return $this->respondWithError(
                trans('validations/api/general/setting/payout/method/update.result.error.create')
            );
        }

        /**
         * Creating payout method request fields
         */
        $this->payoutMethodRequestService->createFields(
            $payoutMethodRequest,
            $request->input('fields')
        );

        /**
         * Getting payment methods with withdrawal status
         */
        $paymentMethods = $this->paymentMethodRepository->getAllWithdrawalIntegrated();

        /**
         * Getting user payout methods
         */
        $userPaymentMethods = $this->paymentMethodService->getForUser(
            $paymentMethods,
            AuthService::user()
        );

        return $this->respondWithSuccess(
            $this->transformItem([], new PayoutMethodListTransformer(
                $paymentMethods,
                $userPaymentMethods,
                $this->paymentMethodImageRepository->getByPaymentMethods(
                    $paymentMethods
                )
            ))['payment_method_list'],
            trans('validations/api/general/setting/payout/method/update.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function cancelRequest(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting payment method
         */
        $paymentMethod = $this->paymentMethodRepository->findFullById($id);

        /**
         * Checking payment method existence
         */
        if (!$paymentMethod) {
            return $this->respondWithError(
                trans('validations/api/general/setting/payout/method/cancelRequest.result.error.find.paymentMethod')
            );
        }

        /**
         * Getting pending payout method request
         */
        $payoutMethodRequest = $this->payoutMethodRequestRepository->findPendingForUser(
            $paymentMethod,
            AuthService::user()
        );

        /**
         * Getting pending payout method request
         */
        if (!$payoutMethodRequest) {
            return $this->respondWithError(
                trans('validations/api/general/setting/payout/method/cancelRequest.result.error.find.payoutMethodRequest')
            );
        }

        /**
         * Updating payout method request
         */
        $this->payoutMethodRequestRepository->updateRequestStatus(
            $payoutMethodRequest,
            RequestStatusList::getCanceledItem()
        );

        /**
         * Getting payment methods with withdrawal status
         */
        $paymentMethods = $this->paymentMethodRepository->getAllWithdrawalIntegrated();

        /**
         * Getting user payout methods
         */
        $userPaymentMethods = $this->paymentMethodService->getForUser(
            $paymentMethods,
            AuthService::user()
        );

        return $this->respondWithSuccess(
            $this->transformItem([], new PayoutMethodListTransformer(
                $paymentMethods,
                $userPaymentMethods,
                $this->paymentMethodImageRepository->getByPaymentMethods(
                    $paymentMethods
                )
            ))['payment_method_list'],
            trans('validations/api/general/setting/payout/method/cancelRequest.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function destroy(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting payment method
         */
        $paymentMethod = $this->paymentMethodRepository->findFullById($id);

        /**
         * Checking payment method existence
         */
        if (!$paymentMethod) {
            return $this->respondWithError(
                trans('validations/api/general/setting/payout/method/destroy.result.error.find.paymentMethod')
            );
        }

        /**
         * Checking payment method for user existence
         */
        if ($this->paymentMethodRepository->existsForUser(
            $paymentMethod,
            AuthService::user()
        )) {

            /**
             * Getting pending payout method request
             */
            if ($this->payoutMethodRequestRepository->findPendingForUser(
                $paymentMethod,
                AuthService::user()
            )) {
                return $this->respondWithError(
                    trans('validations/api/general/setting/payout/method/destroy.result.error.pending')
                );
            }

            /**
             * Detaching payout method
             */
            $this->userRepository->detachPayoutMethod(
                AuthService::user(),
                $paymentMethod
            );
        } else {

            /**
             * Getting payout method request
             */
            $payoutMethodRequest = $this->payoutMethodRequestRepository->findLastForUser(
                $paymentMethod,
                AuthService::user()
            );

            /**
             * Checking payout method request existence
             */
            if (!$payoutMethodRequest) {
                return $this->respondWithError(
                    trans('validations/api/general/setting/payout/method/destroy.result.error.find.payoutMethodRequest')
                );
            }

            /**
             * Checking the payout method request status
             */
            if (!$payoutMethodRequest->getRequestStatus()->isDeclined()) {
                return $this->respondWithError(
                    trans('validations/api/general/setting/payout/method/destroy.result.error.status')
                );
            }

            /**
             * Updating payout method request
             */
            $this->payoutMethodRequestRepository->updateShown(
                $payoutMethodRequest,
                true
            );
        }

        /**
         * Getting payment methods with withdrawal status
         */
        $paymentMethods = $this->paymentMethodRepository->getAllWithdrawalIntegrated();

        /**
         * Getting user payout methods
         */
        $userPaymentMethods = $this->paymentMethodService->getForUser(
            $paymentMethods,
            AuthService::user()
        );

        return $this->respondWithSuccess(
            $this->transformItem([], new PayoutMethodListTransformer(
                $paymentMethods,
                $userPaymentMethods,
                $this->paymentMethodImageRepository->getByPaymentMethods(
                    $paymentMethods
                )
            ))['payment_method_list'],
            trans('validations/api/general/setting/payout/method/destroy.result.success')
        );
    }
}
