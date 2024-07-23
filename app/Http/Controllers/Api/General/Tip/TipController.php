<?php

namespace App\Http\Controllers\Api\General\Tip;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Exceptions\LogException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\General\Tip\Interfaces\TipControllerInterface;
use App\Http\Requests\Api\General\Tip\CancelPaymentRequest;
use App\Http\Requests\Api\General\Tip\ExecutePaymentRequest;
use App\Http\Requests\Api\General\Tip\IndexRequest;
use App\Http\Requests\Api\General\Tip\StoreRequest;
use App\Lists\User\Balance\Type\UserBalanceTypeList;
use App\Repositories\Order\OrderItemRepository;
use App\Repositories\Payment\PaymentMethodRepository;
use App\Repositories\Tip\TipInvoiceRepository;
use App\Repositories\Tip\TipRepository;
use App\Repositories\Tip\TipTransactionRepository;
use App\Repositories\User\UserBalanceRepository;
use App\Services\Admin\AdminNavbarService;
use App\Services\Auth\AuthService;
use App\Services\Log\LogService;
use App\Services\Order\OrderService;
use App\Services\Payment\PaymentMethodService;
use App\Services\Tip\TipService;
use App\Services\User\UserBalanceService;
use App\Transformers\Api\General\Tip\TipPageTransformer;
use App\Transformers\Api\General\Tip\TipPaymentPageTransformer;
use App\Transformers\Api\General\Tip\TipTransformer;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use JsonMapper_Exception;

/**
 * Class TipController
 *
 * @package App\Http\Controllers\Api\General\Tip
 */
final class TipController extends BaseController implements TipControllerInterface
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
     * @var OrderItemRepository
     */
    protected OrderItemRepository $orderItemRepository;

    /**
     * @var OrderService
     */
    protected OrderService $orderService;

    /**
     * @var PaymentMethodRepository
     */
    protected PaymentMethodRepository $paymentMethodRepository;

    /**
     * @var PaymentMethodService
     */
    protected PaymentMethodService $paymentMethodService;

    /**
     * @var TipRepository
     */
    protected TipRepository $tipRepository;

    /**
     * @var TipInvoiceRepository
     */
    protected TipInvoiceRepository $tipInvoiceRepository;

    /**
     * @var TipTransactionRepository
     */
    protected TipTransactionRepository $tipTransactionRepository;

    /**
     * @var TipService
     */
    protected TipService $tipService;

    /**
     * @var UserBalanceRepository
     */
    protected UserBalanceRepository $userBalanceRepository;

    /**
     * @var UserBalanceService
     */
    protected UserBalanceService $userBalanceService;

    /**
     * TipController constructor
     */
    public function __construct()
    {
        /** @var AdminNavbarService adminNavbarService */
        $this->adminNavbarService = new AdminNavbarService();

        /** @var LogService logService */
        $this->logService = new LogService();

        /** @var OrderItemRepository orderItemRepository */
        $this->orderItemRepository = new OrderItemRepository();

        /** @var OrderService orderService */
        $this->orderService = new OrderService();

        /** @var PaymentMethodRepository paymentMethodRepository */
        $this->paymentMethodRepository = new PaymentMethodRepository();

        /** @var PaymentMethodService paymentMethodService */
        $this->paymentMethodService = new PaymentMethodService();

        /** @var TipRepository tipRepository */
        $this->tipRepository = new TipRepository();

        /** @var TipInvoiceRepository tipInvoiceRepository */
        $this->tipInvoiceRepository = new TipInvoiceRepository();

        /** @var TipTransactionRepository tipTransactionRepository */
        $this->tipTransactionRepository = new TipTransactionRepository();

        /** @var TipService tipService */
        $this->tipService = new TipService();

        /** @var UserBalanceRepository userBalanceRepository */
        $this->userBalanceRepository = new UserBalanceRepository();

        /** @var UserBalanceService userBalanceService */
        $this->userBalanceService = new UserBalanceService();
    }

    /**
     * @param IndexRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function index(
        IndexRequest $request
    ) : JsonResponse
    {
        /**
         * Getting order item
         */
        $orderItem = $this->orderItemRepository->findFullById(
            $request->input('item_id')
        );

        /**
         * Checking order item status
         */
        if (!$orderItem->getStatus()->isFinished()) {
            return $this->respondWithError(
                trans('validations/api/general/tip/index.result.error.orderItem.finished')
            );
        }

        /**
         * Checking does buyer tip already exist
         */
        if ($this->tipRepository->existsPaidForBuyer(
            AuthService::user(),
            $orderItem
        )) {
            return $this->respondWithError(
                trans('validations/api/general/tip/index.result.error.exists')
            );
        }

        /**
         * Getting payment method
         */
        $paymentMethod = $this->paymentMethodRepository->findById(
            $request->input('method_id')
        );

        /**
         * Getting payment fee
         */
        $paymentFee = $this->tipService->getPaymentFee(
            $paymentMethod,
            $request->input('amount')
        );

        /**
         * Getting payment fee tax
         */
        $paymentFeeTax = $this->tipService->getAmountTax(
            AuthService::user()->billing,
            $paymentFee
        );

        /**
         * Getting amount tax
         */
        $amountTax = $this->tipService->getAmountTax(
            AuthService::user()->billing,
            $request->input('amount')
        );

        /**
         * Getting amount total
         */
        $amountTotal = array_sum([
            $request->input('amount'),
            $amountTax,
            $paymentFee,
            $paymentFeeTax
        ]);

        return $this->respondWithSuccess(
            $this->transformItem([],
                new TipPageTransformer(
                    $orderItem,
                    $request->input('amount'),
                    $amountTax,
                    $amountTotal,
                    $paymentFee,
                    $paymentFeeTax
                )
            ), trans('validations/api/general/tip/index.result.success')
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
         * Getting order item
         */
        $orderItem = $this->orderItemRepository->findFullById(
            $request->input('item_id')
        );

        /**
         * Checking user is a buyer
         */
        if (!AuthService::user()->is(
            $orderItem->order
                ->buyer
        )) {
            return $this->respondWithError(
                trans('validations/api/general/tip/store.result.error.buyer')
            );
        }

        /**
         * Getting payment method
         */
        $paymentMethod = $this->paymentMethodRepository->findById(
            $request->input('method_id')
        );

        /**
         * Checking payment method
         */
        if (!$this->paymentMethodService->isBalance(
            $paymentMethod
        )) {
            return $this->respondWithError(
                trans('validations/api/general/tip/store.result.error.balance.wrong')
            );
        }

        /**
         * Checking user-buyer balance amount
         */
        if (!$this->userBalanceService->isBuyerBalanceEnough(
            AuthService::user(),
            $request->input('amount')
        )) {
            return $this->respondWithError(
                trans('validations/api/general/tip/store.result.error.balance.enough')
            );
        }

        /**
         * Checking order item status
         */
        if (!$orderItem->getStatus()->isFinished()) {
            return $this->respondWithError(
                trans('validations/api/general/tip/store.result.error.orderItem.finished')
            );
        }

        /**
         * Checking does buyer tip already exist
         */
        if ($this->tipRepository->existsPaidForBuyer(
            AuthService::user(),
            $orderItem
        )) {
            return $this->respondWithError(
                trans('validations/api/general/tip/store.result.error.exists')
            );
        }

        /**
         * Creating tip invoices
         */
        $tip = $this->tipService->createTip(
            AuthService::user(),
            $paymentMethod,
            $orderItem,
            $request->input('amount'),
            $request->input('comment')
        );

        /**
         * Checking tip buyer invoice existence
         */
        if ($tip->getBuyerInvoice()) {

            try {

                /**
                 * Creating tip invoice for buyer log
                 */
                $this->logService->addTipInvoiceForBuyerLog(
                    $tip->getBuyerInvoice(),
                    AuthService::user()->getBuyerBalance(),
                    UserBalanceTypeList::getBuyer(),
                    'created'
                );
            } catch (LogException $exception) {

                /**
                 * Adding background error to controller stack
                 */
                $this->addBackgroundError(
                    $exception
                );
            }
        }

        /**
         * Checking tip seller invoice existence
         */
        if ($tip->getSellerInvoice()) {

            try {

                /**
                 * Creating tip invoice for seller log
                 */
                $this->logService->addTipInvoiceForSellerLog(
                    $tip->getSellerInvoice(),
                    $tip->seller->getSellerBalance(),
                    UserBalanceTypeList::getSeller(),
                    'created'
                );
            } catch (LogException $exception) {

                /**
                 * Adding background error to controller stack
                 */
                $this->addBackgroundError(
                    $exception
                );
            }
        }

        /**
         * Releasing tip counters caches
         */
        $this->adminNavbarService->releaseAllTipCache();

        return $this->respondWithSuccess(
            $this->transformItem([],
                new TipPaymentPageTransformer(
                    $tip,
                    $tip->hash
                )
            )['tip_payment_page'],
            trans('validations/api/general/tip/store.result.success')
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
         * Getting tip
         */
        $tip = $this->tipRepository->findFullById($id);

        /**
         * Checking tip existence
         */
        if (!$tip) {
            return $this->respondWithError(
                trans('validations/api/general/tip/executePayment.result.error.find')
            );
        }

        /**
         * Checking user is an owner
         */
        if (!AuthService::user()->is(
            $tip->buyer
        )) {
            return $this->respondWithError(
                trans('validations/api/general/tip/executePayment.result.error.owner')
            );
        }

        /**
         * Checking tip hash
         */
        if (!$this->tipService->checkHash(
            $tip,
            $request->input('hash')
        )) {
            return $this->respondWithError(
                trans('validations/api/general/tip/executePayment.result.error.hash')
            );
        }

        /**
         * Checking tip invoices statuses
         */
        if (!$this->tipService->checkTipUnpaid(
            $tip
        )) {
            return $this->respondWithError(
                trans('validations/api/general/tip/executePayment.result.error.status')
            );
        }

        /**
         * Checking payment method
         */
        if (!$this->paymentMethodService->isBalance(
            $tip->method
        )) {
            return $this->respondWithError(
                trans('validations/api/general/tip/executePayment.result.error.balance.wrong')
            );
        }

        /**
         * Checking user-buyer balance amount
         */
        if (!$this->userBalanceService->isBuyerBalanceEnough(
            AuthService::user(),
            $tip->amount
        )) {
            return $this->respondWithError(
                trans('validations/api/general/tip/executePayment.result.error.balance.enough')
            );
        }

        /**
         * Executing payment by method
         */
        $tip = $this->tipService->executePayment(
            $tip
        );

        /**
         * Checking tip buyer invoice status
         */
        if ($tip->getBuyerInvoice()
            ->getStatus()
            ->isPaid()
        ) {

            try {

                /**
                 * Creating tip invoice for buyer log
                 */
                $this->logService->addTipInvoiceForBuyerLog(
                    $tip->getBuyerInvoice(),
                    AuthService::user()->getBuyerBalance(),
                    UserBalanceTypeList::getBuyer(),
                    'paid'
                );

            } catch (LogException $exception) {

                /**
                 * Adding background error to controller stack
                 */
                $this->addBackgroundError(
                    $exception
                );
            }
        }

        /**
         * Checking tip seller invoice status
         */
        if ($tip->getSellerInvoice()
            ->getStatus()
            ->isPaid()
        ) {

            try {

                /**
                 * Creating tip invoice for seller log
                 */
                $this->logService->addTipInvoiceForSellerLog(
                    $tip->getSellerInvoice(),
                    $tip->seller->getBuyerBalance(),
                    UserBalanceTypeList::getSeller(),
                    'paid'
                );

            } catch (LogException $exception) {

                /**
                 * Adding background error to controller stack
                 */
                $this->addBackgroundError(
                    $exception
                );
            }
        }

        return $this->respondWithSuccess(
            $this->transformItem($tip, new TipTransformer),
            trans('validations/api/general/tip/executePayment.result.success')
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
         * Getting order invoice
         */
        $tip = $this->tipRepository->findById($id);

        /**
         * Checking tip existence
         */
        if (!$tip) {
            return $this->respondWithError(
                trans('validations/api/general/tip/cancelPayment.result.error.find')
            );
        }

        /**
         * Checking user is an owner
         */
        if (!AuthService::user()->is(
            $tip->buyer
        )) {
            return $this->respondWithError(
                trans('validations/api/general/tip/cancelPayment.result.error.owner')
            );
        }

        /**
         * Checking tip hash
         */
        if (!$this->tipService->checkHash(
            $tip,
            $request->input('hash')
        )) {
            return $this->respondWithError(
                trans('validations/api/general/tip/cancelPayment.result.error.hash')
            );
        }

        /**
         * Checking tip invoices statuses
         */
        if (!$this->tipService->checkTipUnpaid($tip)) {
            return $this->respondWithError(
                trans('validations/api/general/tip/cancelPayment.result.error.status')
            );
        }

        /**
         * Canceling payment by method
         */
        $tip = $this->tipService->cancelPayment(
            $tip
        );

        /**
         * Checking tip buyer invoice status
         */
        if ($tip->getBuyerInvoice()
            ->getStatus()
            ->isCanceled()
        ) {

            try {

                /**
                 * Creating tip invoice for buyer log
                 */
                $this->logService->addTipInvoiceForBuyerLog(
                    $tip->getBuyerInvoice(),
                    AuthService::user()->getBuyerBalance(),
                    UserBalanceTypeList::getBuyer(),
                    'canceled'
                );

            } catch (LogException $exception) {

                /**
                 * Adding background error to controller stack
                 */
                $this->addBackgroundError(
                    $exception
                );
            }
        }

        /**
         * Checking tip seller invoice status
         */
        if ($tip->getSellerInvoice()
            ->getStatus()
            ->isCanceled()
        ) {

            try {

                /**
                 * Creating tip invoice for seller log
                 */
                $this->logService->addTipInvoiceForSellerLog(
                    $tip->getSellerInvoice(),
                    $tip->seller->getBuyerBalance(),
                    UserBalanceTypeList::getSeller(),
                    'canceled'
                );

            } catch (LogException $exception) {

                /**
                 * Adding background error to controller stack
                 */
                $this->addBackgroundError(
                    $exception
                );
            }
        }

        return $this->respondWithSuccess(
            $this->transformItem($tip, new TipTransformer),
            trans('validations/api/general/tip/cancelPayment.result.success')
        );
    }
}
