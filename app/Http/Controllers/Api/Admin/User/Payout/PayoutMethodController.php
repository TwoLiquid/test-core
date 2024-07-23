<?php

namespace App\Http\Controllers\Api\Admin\User\Payout;

use App\Exceptions\DatabaseException;
use App\Exceptions\ValidationException;
use App\Http\Controllers\Api\Admin\User\Payout\Interfaces\PayoutMethodControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\User\Payout\Method\StoreRequest;
use App\Http\Requests\Api\Admin\User\Payout\Method\UpdateRequest;
use App\Repositories\Media\PaymentMethodImageRepository;
use App\Repositories\Payment\PaymentMethodRepository;
use App\Repositories\Payout\PayoutMethodRequestRepository;
use App\Repositories\User\UserRepository;
use App\Services\Payment\PaymentMethodService;
use App\Services\Payout\PayoutMethodRequestService;
use App\Transformers\Api\Admin\User\Payout\Method\PayoutMethodListTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class PayoutMethodController
 *
 * @package App\Http\Controllers\Api\Admin\User\Payout
 */
final class PayoutMethodController extends BaseController implements PayoutMethodControllerInterface
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
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function index(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting user
         */
        $user = $this->userRepository->findById($id);

        /**
         * Checking user existence
         */
        if (!$user) {
            return $this->respondWithError(
                trans('validations/api/admin/user/payout/method/index.result.error.find')
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
            $user,
            true
        );

        return $this->respondWithSuccess(
            $this->transformItem([], new PayoutMethodListTransformer(
                $paymentMethods,
                $userPaymentMethods,
                $this->paymentMethodImageRepository->getByPaymentMethods(
                    $paymentMethods
                )
            ))['payment_method_list'],
            trans('validations/api/admin/user/payout/method/index.result.success')
        );
    }

    /**
     * @param int $id
     * @param StoreRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws ValidationException
     */
    public function store(
        int $id,
        StoreRequest $request
    ) : JsonResponse
    {
        /**
         * Getting user
         */
        $user = $this->userRepository->findById($id);

        /**
         * Checking user existence
         */
        if (!$user) {
            return $this->respondWithError(
                trans('validations/api/admin/user/payout/method/store.result.error.find.user')
            );
        }

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
                trans('validations/api/admin/user/payout/method/store.result.error.find.paymentMethod')
            );
        }

        /**
         * Checking payment method status
         */
        if (!$paymentMethod->getWithdrawalStatus()->isActive()) {
            return $this->respondWithError(
                trans('validations/api/admin/user/payout/method/store.result.error.status')
            );
        }

        /**
         * Checking payment method for user existence
         */
        if ($this->paymentMethodRepository->existsForUser(
            $paymentMethod,
            $user
        )) {
            return $this->respondWithError(
                trans('validations/api/admin/user/payout/method/store.result.error.exists')
            );
        }

        /**
         * Getting pending payout method request
         */
        if ($this->payoutMethodRequestRepository->findPendingForUser(
            $paymentMethod,
            $user
        )) {
            return $this->respondWithError(
                trans('validations/api/admin/user/payout/method/store.result.error.pending')
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
         * Attaching payout method
         */
        $this->userRepository->attachPayoutMethod(
            $user,
            $paymentMethod
        );

        /**
         * Creating payout method request fields
         */
        $this->paymentMethodService->updateFieldsUserValues(
            $user,
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
            $user,
            true
        );

        return $this->respondWithSuccess(
            $this->transformItem([], new PayoutMethodListTransformer(
                $paymentMethods,
                $userPaymentMethods,
                $this->paymentMethodImageRepository->getByPaymentMethods(
                    $paymentMethods
                )
            ))['payment_method_list'],
            trans('validations/api/admin/user/payout/method/store.result.success')
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
         * Getting user
         */
        $user = $this->userRepository->findById($id);

        /**
         * Checking user existence
         */
        if (!$user) {
            return $this->respondWithError(
                trans('validations/api/admin/user/payout/method/update.result.error.find.user')
            );
        }

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
                trans('validations/api/admin/user/payout/method/update.result.error.find.paymentMethod')
            );
        }

        /**
         * Checking payment method status
         */
        if (!$paymentMethod->getWithdrawalStatus()->isActive()) {
            return $this->respondWithError(
                trans('validations/api/admin/user/payout/method/update.result.error.status')
            );
        }

        /**
         * Checking payment method for user existence
         */
        if (!$this->paymentMethodRepository->existsForUser(
            $paymentMethod,
            $user
        )) {
            return $this->respondWithError(
                trans('validations/api/admin/user/payout/method/update.result.error.exists')
            );
        }

        /**
         * Getting pending payout method request
         */
        if ($this->payoutMethodRequestRepository->findPendingForUser(
            $paymentMethod,
            $user
        )) {
            return $this->respondWithError(
                trans('validations/api/admin/user/payout/method/update.result.error.pending')
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
         * Creating payout method request fields
         */
        $this->paymentMethodService->updateFieldsUserValues(
            $user,
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
            $user,
            true
        );

        return $this->respondWithSuccess(
            $this->transformItem([], new PayoutMethodListTransformer(
                $paymentMethods,
                $userPaymentMethods,
                $this->paymentMethodImageRepository->getByPaymentMethods(
                    $paymentMethods
                )
            ))['payment_method_list'],
            trans('validations/api/admin/user/payout/method/update.result.success')
        );
    }

    /**
     * @param int $id
     * @param int $methodId
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function destroy(
        int $id,
        int $methodId
    ) : JsonResponse
    {
        /**
         * Getting user
         */
        $user = $this->userRepository->findById($id);

        /**
         * Checking user existence
         */
        if (!$user) {
            return $this->respondWithError(
                trans('validations/api/admin/user/payout/method/destroy.result.error.find.user')
            );
        }

        /**
         * Getting payment method
         */
        $paymentMethod = $this->paymentMethodRepository->findFullById(
            $methodId
        );

        /**
         * Checking payment method existence
         */
        if (!$paymentMethod) {
            return $this->respondWithError(
                trans('validations/api/admin/user/payout/method/destroy.result.error.find.paymentMethod')
            );
        }

        /**
         * Checking payment method for user existence
         */
        if (!$this->paymentMethodRepository->existsForUser(
            $paymentMethod,
            $user
        )) {
            return $this->respondWithError(
                trans('validations/api/admin/user/payout/method/destroy.result.error.exists')
            );
        }

        /**
         * Getting pending payout method request
         */
        if ($this->payoutMethodRequestRepository->findPendingForUser(
            $paymentMethod,
            $user
        )) {
            return $this->respondWithError(
                trans('validations/api/admin/user/payout/method/destroy.result.error.pending')
            );
        }

        /**
         * Detaching payout method
         */
        $this->userRepository->detachPayoutMethod(
            $user,
            $paymentMethod
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
            $user,
            true
        );

        return $this->respondWithSuccess(
            $this->transformItem([], new PayoutMethodListTransformer(
                $paymentMethods,
                $userPaymentMethods,
                $this->paymentMethodImageRepository->getByPaymentMethods(
                    $paymentMethods
                )
            ))['payment_method_list'],
            trans('validations/api/admin/user/payout/method/destroy.result.success')
        );
    }
}
