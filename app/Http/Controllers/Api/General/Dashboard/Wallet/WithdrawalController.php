<?php

namespace App\Http\Controllers\Api\General\Dashboard\Wallet;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\General\Dashboard\Wallet\Interfaces\WithdrawalControllerInterface;
use App\Http\Requests\Api\General\Dashboard\Wallet\Withdrawal\StoreRequest;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\User\Balance\Type\UserBalanceTypeList;
use App\Repositories\Payment\PaymentMethodRepository;
use App\Repositories\Receipt\WithdrawalRequestRepository;
use App\Services\Admin\AdminNavbarService;
use App\Services\Auth\AuthService;
use App\Services\Log\LogService;
use App\Services\Payment\PaymentMethodService;
use App\Services\Withdrawal\WithdrawalRequestService;
use App\Transformers\Api\General\Dashboard\Wallet\Withdrawal\WithdrawalRequestTransformer;
use App\Transformers\Api\General\Dashboard\Wallet\Withdrawal\WithdrawalTransformer;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Exception;

/**
 * Class WithdrawalController
 *
 * @package App\Http\Controllers\Api\General\Dashboard\Wallet
 */
final class WithdrawalController extends BaseController implements WithdrawalControllerInterface
{
    /**
     * @var AdminNavbarService
     */
    protected AdminNavbarService $adminNavbarService;

    /**
     * @var LogService
     */
    protected LogService $logService;

    /**
     * @var PaymentMethodRepository
     */
    protected PaymentMethodRepository $paymentMethodRepository;

    /**
     * @var PaymentMethodService
     */
    protected PaymentMethodService $paymentMethodService;

    /**
     * @var WithdrawalRequestRepository
     */
    protected WithdrawalRequestRepository $withdrawalRequestRepository;

    /**
     * @var WithdrawalRequestService
     */
    protected WithdrawalRequestService $withdrawalRequestService;

    /**
     * WithdrawalController constructor
     */
    public function __construct()
    {
        /** @var AdminNavbarService adminNavbarService */
        $this->adminNavbarService = new AdminNavbarService();

        /** @var LogService logService */
        $this->logService = new LogService();

        /** @var PaymentMethodRepository paymentMethodRepository */
        $this->paymentMethodRepository = new PaymentMethodRepository();

        /** @var PaymentMethodService paymentMethodService */
        $this->paymentMethodService = new PaymentMethodService();

        /** @var WithdrawalRequestRepository withdrawalRequestRepository */
        $this->withdrawalRequestRepository = new WithdrawalRequestRepository();

        /** @var WithdrawalRequestService withdrawalRequestService */
        $this->withdrawalRequestService = new WithdrawalRequestService();
    }

    /**
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function index() : JsonResponse
    {
        /**
         * Getting user payout methods
         */
        $payoutMethods = AuthService::user()
            ->payoutMethods;

        /**
         * Getting user withdrawal requests
         */
        $withdrawalRequests = $this->withdrawalRequestRepository->getPendingAndDeclinedForUser(
            AuthService::user()
        );

        return $this->respondWithSuccess(
            $this->transformItem([],
                new WithdrawalTransformer(
                    $payoutMethods,
                    $withdrawalRequests
                )
            )['withdrawal'],
            trans('validations/api/general/dashboard/wallet/withdrawal/index.result.success')
        );
    }

    /**
     * @param StoreRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     */
    public function store(
        StoreRequest $request
    ) : JsonResponse
    {
        /**
         * Checking user seller balance is available for withdrawal
         */
        if (!$this->withdrawalRequestService->isAvailableForWithdrawal(
            AuthService::user(),
            $request->input('amount')
        )) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/wallet/withdrawal/store.result.error.available')
            );
        }

        /**
         * Getting payout method
         */
        $payoutMethod = $this->paymentMethodRepository->findById(
            $request->input('method_id')
        );

        /**
         * Checking payment method
         */
        if ($this->paymentMethodService->isBalance(
            $payoutMethod
        )) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/wallet/withdrawal/store.result.error.balance')
            );
        }

        /**
         * Creating withdrawal request
         */
        $withdrawalRequest = $this->withdrawalRequestRepository->store(
            AuthService::user(),
            $payoutMethod,
            $request->input('amount')
        );

        /**
         * Checking withdrawal request existence
         */
        if (!$withdrawalRequest) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/wallet/withdrawal/store.result.error.create')
            );
        }

        /**
         * Releasing withdrawal counters caches
         */
        $this->adminNavbarService->releaseAllWithdrawalCache();

        try {

            /**
             * Creating withdrawal request log
             */
            $this->logService->addWithdrawalRequestLog(
                $withdrawalRequest,
                AuthService::user()->getSellerBalance(),
                UserBalanceTypeList::getSeller(),
                'created'
            );
        } catch (Exception $exception) {

            /**
             * Adding background error to controller stack
             */
            $this->addBackgroundError(
                $exception
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($withdrawalRequest, new WithdrawalRequestTransformer),
            trans('validations/api/general/dashboard/wallet/withdrawal/store.result.success')
        );
    }

    /**
     * @param string $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function closeRequest(
        string $id
    ) : JsonResponse
    {
        /**
         * Getting withdrawal request
         */
        $withdrawalRequest = $this->withdrawalRequestRepository->findById($id);

        /**
         * Checking withdrawal request existence
         */
        if (!$withdrawalRequest) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/wallet/withdrawal/closeRequest.result.error.find')
            );
        }

        /**
         * Checking is an owner
         */
        if (!$withdrawalRequest->user
            ->is(AuthService::user())
        ) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/wallet/withdrawal/closeRequest.result.error.owner')
            );
        }

        /**
         * Checking the withdrawal request status
         */
        if (!$withdrawalRequest->getStatus()->isDeclined()) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/wallet/withdrawal/closeRequest.result.error.status')
            );
        }

        /**
         * Updating withdrawal request
         */
        $this->withdrawalRequestRepository->updateShown(
            $withdrawalRequest,
            true
        );

        return $this->respondWithSuccess([],
            trans('validations/api/general/dashboard/wallet/withdrawal/closeRequest.result.success')
        );
    }

    /**
     * @param string $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     */
    public function cancelRequest(
        string $id
    ) : JsonResponse
    {
        /**
         * Getting withdrawal request
         */
        $withdrawalRequest = $this->withdrawalRequestRepository->findById($id);

        /**
         * Checking withdrawal request existence
         */
        if (!$withdrawalRequest) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/wallet/withdrawal/cancelRequest.result.error.find')
            );
        }

        /**
         * Checking withdrawal request status
         */
        if (!$withdrawalRequest->getStatus()->isPending()) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/wallet/withdrawal/cancelRequest.result.error.pending')
            );
        }

        /**
         * Updating withdrawal request status
         */
        $this->withdrawalRequestRepository->updateRequestStatus(
            $withdrawalRequest,
            RequestStatusList::getCanceledItem()
        );

        try {

            /**
             * Creating withdrawal request log
             */
            $this->logService->addWithdrawalRequestLog(
                $withdrawalRequest,
                AuthService::user()->getSellerBalance(),
                UserBalanceTypeList::getSeller(),
                'canceled'
            );
        } catch (Exception $exception) {

            /**
             * Adding background error to controller stack
             */
            $this->addBackgroundError(
                $exception
            );
        }

        /**
         * Releasing withdrawal counters caches
         */
        $this->adminNavbarService->releaseAllWithdrawalCache();

        return $this->respondWithSuccess([],
            trans('validations/api/general/dashboard/wallet/withdrawal/cancelRequest.result.success')
        );
    }
}
