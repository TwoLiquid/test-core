<?php

namespace App\Http\Controllers\Api\General\Dashboard\Wallet;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\General\Dashboard\Wallet\Interfaces\AddFundsControllerInterface;
use App\Http\Requests\Api\General\Dashboard\Wallet\AddFunds\CancelPaymentRequest;
use App\Http\Requests\Api\General\Dashboard\Wallet\AddFunds\StoreRequest;
use App\Http\Requests\Api\General\Dashboard\Wallet\AddFunds\ExecutePaymentRequest;
use App\Lists\AddFunds\Receipt\Status\AddFundsReceiptStatusList;
use App\Lists\User\Balance\Type\UserBalanceTypeList;
use App\Microservices\Log\LogMicroservice;
use App\Repositories\Payment\PaymentMethodRepository;
use App\Repositories\Receipt\AddFundsReceiptRepository;
use App\Repositories\User\UserBalanceRepository;
use App\Services\AddFunds\AddFundsReceiptService;
use App\Services\Admin\AdminNavbarService;
use App\Services\Auth\AuthService;
use App\Services\Log\LogService;
use App\Services\Payment\PaymentMethodService;
use App\Services\Payment\PayPalService;
use App\Transformers\Api\General\Dashboard\Wallet\AddFunds\AddFundsPageTransformer;
use App\Transformers\Api\General\Dashboard\Wallet\AddFunds\AddFundsReceiptTransformer;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use JsonMapper_Exception;
use Exception;

/**
 * Class AddFundsController
 *
 * @package App\Http\Controllers\Api\General\Dashboard\Wallet
 */
final class AddFundsController extends BaseController implements AddFundsControllerInterface
{
    /**
     * @var AddFundsReceiptRepository
     */
    protected AddFundsReceiptRepository $addFundsReceiptRepository;

    /**
     * @var AddFundsReceiptService
     */
    protected AddFundsReceiptService $addFundsReceiptService;

    /**
     * @var AdminNavbarService
     */
    protected AdminNavbarService $adminNavbarService;

    /**
     * @var LogMicroservice
     */
    protected LogMicroservice $logMicroservice;

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
     * @var PayPalService
     */
    protected PayPalService $payPalService;

    /**
     * @var UserBalanceRepository
     */
    protected UserBalanceRepository $userBalanceRepository;

    /**
     * AddFundsController constructor
     */
    public function __construct()
    {
        /** @var AddFundsReceiptRepository addFundsReceiptRepository */
        $this->addFundsReceiptRepository = new AddFundsReceiptRepository();

        /** @var AddFundsReceiptService addFundsReceiptService */
        $this->addFundsReceiptService = new AddFundsReceiptService();

        /** @var AdminNavbarService adminNavbarService */
        $this->adminNavbarService = new AdminNavbarService();

        /** @var LogMicroservice logMicroservice */
        $this->logMicroservice = new LogMicroservice();

        /** @var LogService logService */
        $this->logService = new LogService();

        /** @var PaymentMethodRepository paymentMethodRepository */
        $this->paymentMethodRepository = new PaymentMethodRepository();

        /** @var PaymentMethodService paymentMethodService */
        $this->paymentMethodService = new PaymentMethodService();

        /** @var PayPalService payPalService */
        $this->payPalService = new PayPalService();

        /** @var UserBalanceRepository userBalanceRepository */
        $this->userBalanceRepository = new UserBalanceRepository();
    }

    /**
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function index() : JsonResponse
    {
        /**
         * Getting payment methods
         */
        $paymentMethods = $this->paymentMethodRepository
            ->getAllPaymentIntegrated(false);

        return $this->respondWithSuccess(
            $this->transformItem([],
                new AddFundsPageTransformer(
                    $paymentMethods,
                    null,
                    null
                )
            )['add_funds_page'],
            trans('validations/api/general/dashboard/wallet/addFunds/index.result.success')
        );
    }

    /**
     * @param StoreRequest $request
     *
     * @return JsonResponse
     *
     * @throws BaseException
     * @throws DatabaseException
     * @throws GuzzleException
     */
    public function store(
        StoreRequest $request
    ) : JsonResponse
    {
        /**
         * Getting payment method
         */
        $paymentMethod = $this->paymentMethodRepository->findById(
            $request->input('method_id')
        );

        /**
         * Checking payment method
         */
        if ($this->paymentMethodService->isBalance(
            $paymentMethod
        )) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/wallet/addFunds/store.result.error.balance')
            );
        }

        /**
         * Creating add funds receipt
         */
        $addFundsReceipt = $this->addFundsReceiptRepository->store(
            AuthService::user(),
            $paymentMethod,
            AddFundsReceiptStatusList::getUnpaid(),
            null,
            $request->input('amount'),
            null,
            null
        );

        /**
         * Checking add funds receipt existence
         */
        if (!$addFundsReceipt) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/wallet/addFunds/store.result.error.create')
            );
        }

        /**
         * Getting add funds payment url
         */
        $paymentUrl = $this->addFundsReceiptService->getPaymentUrl(
            $addFundsReceipt
        );

        /**
         * Checking payment url existence
         */
        if (!$paymentUrl) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/wallet/addFunds/store.result.error.paymentUrl')
            );
        }

        /**
         * Releasing add funds receipts counters caches
         */
        $this->adminNavbarService->releaseAddFundsCache();

        try {

            /**
             * Creating add funds receipt log
             */
            $this->logService->addFundsReceiptLog(
                $addFundsReceipt,
                AuthService::user()->getBuyerBalance(),
                UserBalanceTypeList::getBuyer(),
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
            $this->transformItem([],
                new AddFundsPageTransformer(
                    null,
                    $addFundsReceipt,
                    $paymentUrl
                )
            )['add_funds_page'],
            trans('validations/api/general/dashboard/wallet/addFunds/store.result.success')
        );
    }

    /**
     * @param int $id
     * @param ExecutePaymentRequest $request
     *
     * @return JsonResponse
     *
     * @throws BaseException
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws JsonMapper_Exception
     */
    public function executePayment(
        int $id,
        ExecutePaymentRequest $request
    ) : JsonResponse
    {
        /**
         * Getting add funds receipt
         */
        $addFundsReceipt = $this->addFundsReceiptRepository->findFullById($id);

        /**
         * Checking add funds status
         */
        if (!$addFundsReceipt) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/wallet/addFunds/executePayment.result.error.find')
            );
        }

        /**
         * Checking user is an owner
         */
        if (!AuthService::user()->is(
            $addFundsReceipt->user
        )) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/wallet/addFunds/executePayment.result.error.owner')
            );
        }

        /**
         * Checking order hash
         */
        if (!$this->addFundsReceiptService->checkHash(
            $addFundsReceipt,
            $request->input('hash')
        )) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/wallet/addFunds/executePayment.result.error.hash')
            );
        }

        /**
         * Checking add funds status
         */
        if (!$addFundsReceipt->getStatus()->isUnpaid()) {

            /**
             * Checking add funds status is already paid
             */
            if ($addFundsReceipt->getStatus()->isPaid()) {
                return $this->respondWithSuccess(
                    $this->transformItem($addFundsReceipt, new AddFundsReceiptTransformer),
                    trans('validations/api/general/dashboard/wallet/addFunds/executePayment.result.success')
                );
            }

            return $this->respondWithError(
                trans('validations/api/general/dashboard/wallet/addFunds/executePayment.result.error.status')
            );
        }

        /**
         * Executing add funds payment
         */
        $addFundsReceipt = $this->addFundsReceiptService->executePayment(
            $addFundsReceipt
        );

        /**
         * Checking add funds receipt status
         */
        if ($addFundsReceipt->getStatus()->isPaid()) {

            /**
             * Increasing user buyer balance
             */
            $this->userBalanceRepository->increaseAmount(
                AuthService::user()->getBuyerBalance(),
                $addFundsReceipt->amount
            );

            try {

                /**
                 * Creating add funds receipt log
                 */
                $this->logService->addFundsReceiptLog(
                    $addFundsReceipt,
                    AuthService::user()->getBuyerBalance(),
                    UserBalanceTypeList::getBuyer(),
                    'paid'
                );
            } catch (Exception $exception) {

                /**
                 * Adding background error to controller stack
                 */
                $this->addBackgroundError(
                    $exception
                );
            }
        }

        return $this->respondWithSuccess(
            $this->transformItem($addFundsReceipt, new AddFundsReceiptTransformer),
            trans('validations/api/general/dashboard/wallet/addFunds/executePayment.result.success')
        );
    }

    /**
     * @param int $id
     * @param CancelPaymentRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     */
    public function cancelPayment(
        int $id,
        CancelPaymentRequest $request
    ) : JsonResponse
    {
        /**
         * Getting add funds receipts
         */
        $addFundsReceipt = $this->addFundsReceiptRepository->findFullById($id);

        /**
         * Checking add funds status
         */
        if (!$addFundsReceipt) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/wallet/addFunds/cancelPayment.result.error.find')
            );
        }

        /**
         * Checking user is an owner
         */
        if (!AuthService::user()->is(
            $addFundsReceipt->user
        )) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/wallet/addFunds/cancelPayment.result.error.owner')
            );
        }

        /**
         * Checking order hash
         */
        if (!$this->addFundsReceiptService->checkHash(
            $addFundsReceipt,
            $request->input('hash')
        )) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/wallet/addFunds/cancelPayment.result.error.hash')
            );
        }

        /**
         * Checking add funds status
         */
        if (!$addFundsReceipt->getStatus()->isUnpaid()) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/wallet/addFunds/cancelPayment.result.error.status')
            );
        }

        /**
         * Updating add funds receipt
         */
        $addFundsReceipt = $this->addFundsReceiptRepository->updateStatus(
            $addFundsReceipt,
            AddFundsReceiptStatusList::getCanceled()
        );

        /**
         * Checking add funds receipt status
         */
        if ($addFundsReceipt->getStatus()->isCanceled()) {

            try {

                /**
                 * Creating add funds receipt log
                 */
                $this->logService->addFundsReceiptLog(
                    $addFundsReceipt,
                    AuthService::user()->getBuyerBalance(),
                    UserBalanceTypeList::getBuyer(),
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
        }

        return $this->respondWithSuccess(
            $this->transformItem($addFundsReceipt, new AddFundsReceiptTransformer),
            trans('validations/api/general/dashboard/wallet/addFunds/cancelPayment.result.success')
        );
    }
}
