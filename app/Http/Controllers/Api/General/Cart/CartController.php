<?php

namespace App\Http\Controllers\Api\General\Cart;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Exceptions\LogException;
use App\Exceptions\ValidationException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\General\Cart\Interfaces\CartControllerInterface;
use App\Http\Requests\Api\General\Cart\CheckoutCancelRequest;
use App\Http\Requests\Api\General\Cart\CheckoutExecuteRequest;
use App\Http\Requests\Api\General\Cart\CheckoutRequest;
use App\Http\Requests\Api\General\Cart\IndexRequest;
use App\Http\Requests\Api\General\Cart\RefreshRequest;
use App\Lists\User\Balance\Status\UserBalanceStatusList;
use App\Lists\User\Balance\Type\UserBalanceTypeList;
use App\Repositories\AppearanceCase\AppearanceCaseRepository;
use App\Repositories\Cart\CartItemRepository;
use App\Repositories\Media\ActivityImageRepository;
use App\Repositories\Media\UserAvatarRepository;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Payment\PaymentMethodRepository;
use App\Repositories\User\UserBalanceRepository;
use App\Repositories\User\UserRepository;
use App\Services\Activity\ActivityService;
use App\Services\Admin\AdminNavbarService;
use App\Services\Auth\AuthService;
use App\Services\Cart\CartService;
use App\Services\Log\LogService;
use App\Services\Order\OrderItemService;
use App\Services\Order\OrderService;
use App\Services\Payment\PaymentMethodService;
use App\Services\Sale\SaleService;
use App\Services\Schedule\ScheduleService;
use App\Services\Timeslot\TimeslotService;
use App\Services\User\UserBalanceService;
use App\Services\User\UserService;
use App\Transformers\Api\General\Cart\CartItemPageTransformer;
use App\Transformers\Api\General\Cart\Checkout\CheckoutPageTransformer;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use JsonMapper_Exception;

/**
 * Class CartController
 *
 * @package App\Http\Controllers\Api\General\Cart
 */
final class CartController extends BaseController implements CartControllerInterface
{
    /**
     * @var ActivityImageRepository
     */
    protected ActivityImageRepository $activityImageRepository;

    /**
     * @var ActivityService
     */
    protected ActivityService $activityService;

    /**
     * @var AdminNavbarService
     */
    protected AdminNavbarService $adminNavbarService;

    /**
     * @var AppearanceCaseRepository
     */
    protected AppearanceCaseRepository $appearanceCaseRepository;

    /**
     * @var CartItemRepository
     */
    protected CartItemRepository $cartItemRepository;

    /**
     * @var CartService
     */
    protected CartService $cartService;

    /**
     * @var LogService
     */
    protected LogService $logService;

    /**
     * @var OrderItemService
     */
    protected OrderItemService $orderItemService;

    /**
     * @var OrderRepository
     */
    protected OrderRepository $orderRepository;

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
     * @var SaleService
     */
    protected SaleService $saleService;

    /**
     * @var ScheduleService
     */
    protected ScheduleService $scheduleService;

    /**
     * @var TimeslotService
     */
    protected TimeslotService $timeslotService;

    /**
     * @var UserAvatarRepository
     */
    protected UserAvatarRepository $userAvatarRepository;

    /**
     * @var UserBalanceRepository
     */
    protected UserBalanceRepository $userBalanceRepository;

    /**
     * @var UserBalanceService
     */
    protected UserBalanceService $userBalanceService;

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * @var UserService
     */
    protected UserService $userService;

    /**
     * CartController constructor
     */
    public function __construct()
    {
        /** @var ActivityImageRepository activityImageRepository */
        $this->activityImageRepository = new ActivityImageRepository();

        /** @var ActivityService activityService */
        $this->activityService = new ActivityService();

        /** @var AdminNavbarService adminNavbarService */
        $this->adminNavbarService = new AdminNavbarService();

        /** @var AppearanceCaseRepository appearanceCaseRepository */
        $this->appearanceCaseRepository = new AppearanceCaseRepository();

        /** @var CartItemRepository cartItemRepository */
        $this->cartItemRepository = new CartItemRepository();

        /** @var CartService cartService */
        $this->cartService = new CartService();

        /** @var LogService logService */
        $this->logService = new LogService();

        /** @var OrderItemService orderItemService */
        $this->orderItemService = new OrderItemService();

        /** @var OrderRepository orderRepository */
        $this->orderRepository = new OrderRepository();

        /** @var OrderService orderService */
        $this->orderService = new OrderService();

        /** @var PaymentMethodRepository paymentMethodRepository */
        $this->paymentMethodRepository = new PaymentMethodRepository();

        /** @var PaymentMethodService paymentMethodService */
        $this->paymentMethodService = new PaymentMethodService();

        /** @var SaleService saleService */
        $this->saleService = new SaleService();

        /** @var ScheduleService scheduleService */
        $this->scheduleService = new ScheduleService();

        /** @var TimeslotService timeslotService */
        $this->timeslotService = new TimeslotService();

        /** @var UserAvatarRepository userAvatarRepository */
        $this->userAvatarRepository = new UserAvatarRepository();

        /** @var UserBalanceRepository userBalanceRepository */
        $this->userBalanceRepository = new UserBalanceRepository();

        /** @var UserBalanceService userBalanceService */
        $this->userBalanceService = new UserBalanceService();

        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();

        /** @var UserService userService */
        $this->userService = new UserService();
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
         * Getting cart items
         */
        $cartItems = $this->cartService->getRefreshedItems(
            AuthService::user()
        );

        return $this->respondWithSuccess(
            $this->transformItem(
                $cartItems,
                new CartItemPageTransformer(
                    $this->userRepository->findWithSubscriptions(
                        AuthService::user()
                    ),
                    $cartItems,
                    $this->timeslotService->getByCartItems(
                        $cartItems
                    ),
                    $this->activityImageRepository->getByActivities(
                        $this->activityService->getByCartItems(
                            $cartItems
                        )
                    ),
                    $this->userAvatarRepository->getByUsers(
                        $this->userService->getByCartItems(
                            $cartItems
                        )
                    ),
                    $request->input('with_form')
                )
            )['cart_item_page'],
            trans('validations/api/general/cart/index.result.success')
        );
    }

    /**
     * @param RefreshRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function refresh(
        RefreshRequest $request
    ) : JsonResponse
    {
        /**
         * Updating user cart items
         */
        $this->cartService->refresh(
            AuthService::user(),
            $request->input('cart_items')
        );

        /**
         * Getting cart items
         */
        $cartItems = $this->cartService->getRefreshedItems(
            AuthService::user()
        );

        return $this->respondWithSuccess(
            $this->transformItem(
                $cartItems,
                new CartItemPageTransformer(
                    $this->userRepository->findWithSubscriptions(
                        AuthService::user()
                    ),
                    $cartItems,
                    $this->timeslotService->getByCartItems(
                        $cartItems
                    ),
                    $this->activityImageRepository->getByActivities(
                        $this->activityService->getByCartItems(
                            $cartItems
                        )
                    ),
                    $this->userAvatarRepository->getByUsers(
                        $this->userService->getByCartItems(
                            $cartItems
                        )
                    ),
                    $request->input('with_form')
                )
            )['cart_item_page'],
            trans('validations/api/general/cart/refresh.result.success')
        );
    }

    /**
     * @param CheckoutRequest $request
     *
     * @return JsonResponse
     *
     * @throws BaseException
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws ValidationException
     */
    public function checkout(
        CheckoutRequest $request
    ) : JsonResponse
    {
        /**
         * Getting user cart items
         */
        $cartItems = $this->cartItemRepository->getByUser(
            AuthService::user()
        );

        /**
         * Checking is cart items empty
         */
        if ($cartItems->isEmpty()) {
            return $this->respondWithError(
                trans('validations/api/general/cart/checkout.result.error.cartItems')
            );
        }

        /**
         * Checking timeslot availabilities
         */
        $this->cartService->checkTimeslotAvailability(
            AuthService::user(),
            $cartItems->toArray()
        );

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
                trans('validations/api/general/cart/checkout.result.error.balance.wrong')
            );
        }

        /**
         * Checking user-buyer balance amount
         */
        if (!$this->userBalanceService->isBuyerBalanceEnough(
            AuthService::user(),
            $this->orderItemService->getPreAmountTotal(
                AuthService::user(),
                $cartItems->toArray()
            )
        )) {
            return $this->respondWithError(
                trans('validations/api/general/cart/checkout.result.error.balance.enough')
            );
        }

        /**
         * Creating order
         */
        $order = $this->orderService->createOrder(
            AuthService::user(),
            $paymentMethod,
            $cartItems->toArray()
        );

        /**
         * Checking order existence
         */
        if (!$order) {
            return $this->respondWithError(
                trans('validations/api/general/cart/checkout.result.error.create.order')
            );
        }

        /**
         * Sending notifications to all order items sellers
         */
        $this->orderItemService->sendToAllSellers(
            $order->items
        );

        try {

            /**
             * Creating order overview log
             */
            $this->logService->addOrderOverviewLog(
                $order,
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

        try {

            /**
             * Creating order invoice for buyer log
             */
            $this->logService->addInvoiceForBuyerLog(
                $order->getBuyerInvoice(),
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

        /**
         * Updating user buyer status
         */
        $this->userBalanceRepository->updateStatus(
            AuthService::user()->getBuyerBalance(),
            UserBalanceStatusList::getActive()
        );

        /**
         * Releasing user cart
         */
        $this->cartItemRepository->deleteForUser(
            AuthService::user()
        );

        /**
         * Releasing user counters caches
         */
        $this->adminNavbarService->releaseAllUserCache();

        /**
         * Releasing order counters caches
         */
        $this->adminNavbarService->releaseAllOrderCache();

        return $this->respondWithSuccess(
            $this->transformItem([],
                new CheckoutPageTransformer(
                    $order,
                    $order->hash
                )
            )['checkout_page'],
            trans('validations/api/general/cart/checkout.result.success')
        );
    }

    /**
     * @param CheckoutExecuteRequest $request
     *
     * @return JsonResponse
     *
     * @throws BaseException
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws JsonMapper_Exception
     */
    public function checkoutExecute(
        CheckoutExecuteRequest $request
    ) : JsonResponse
    {
        /**
         * Getting order
         */
        $order = $this->orderRepository->findFullById(
            $request->input('order_id')
        );

        /**
         * Checking order existence
         */
        if (!$order) {
            return $this->respondWithError(
                trans('validations/api/general/cart/checkoutExecute.result.error.order.find')
            );
        }

        /**
         * Checking user is a buyer
         */
        if (!AuthService::user()->is(
            $order->buyer
        )) {
            return $this->respondWithError(
                trans('validations/api/general/cart/checkoutExecute.result.error.buyer')
            );
        }

        /**
         * Checking order hash
         */
        if (!$this->orderService->checkHash(
            $order,
            $request->input('hash')
        )) {
            return $this->respondWithError(
                trans('validations/api/general/cart/checkoutExecute.result.error.hash')
            );
        }

        /**
         * Checking order invoice existence
         */
        if (!$order->getBuyerInvoice()) {
            return $this->respondWithError(
                trans('validations/api/general/cart/checkoutExecute.result.error.orderInvoice.find')
            );
        }

        /**
         * Checking order invoice status
         */
        if (!$order->getBuyerInvoice()
            ->getStatus()
            ->isUnpaid()
        ) {
            return $this->respondWithError(
                trans('validations/api/general/cart/checkoutExecute.result.error.orderInvoice.status')
            );
        }

        /**
         * Checking payment method
         */
        if (!$this->paymentMethodService->isBalance(
            $order->method
        )) {
            return $this->respondWithError(
                trans('validations/api/general/cart/checkoutExecute.result.error.balance.wrong')
            );
        }

        /**
         * Checking user-buyer balance amount
         */
        if (!$this->userBalanceService->isBuyerBalanceEnough(
            AuthService::user(),
            $order->amount_total
        )) {
            return $this->respondWithError(
                trans('validations/api/general/cart/checkoutExecute.result.error.balance.enough')
            );
        }

        /**
         * Executing payment by method
         */
        $order = $this->orderService->executePayment(
            $order
        );

        /**
         * Updating all paid order documents
         */
        $this->orderService->updatePaidOrder(
            $order
        );

        /**
         * Checking order invoice status
         */
        if ($order->getBuyerInvoice()
            ->getStatus()
            ->isPaid()
        ) {

            /**
             * Updating user balance status
             */
            $this->userBalanceRepository->updateStatus(
                $order->buyer->getBuyerBalance(),
                UserBalanceStatusList::getActive()
            );

            /**
             * Creating order item pending requests
             */
            $this->orderItemService->createPendingRequests(
                $order->items
            );

            /**
             * Processing order items
             */
            $this->orderItemService->processPaidOrderItems(
                $order
            );

            try {

                /**
                 * Creating order invoice log
                 */
                $this->logService->addInvoiceForBuyerLog(
                    $order->getBuyerInvoice(),
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

            /**
             * Creating sale overviews
             */
            $saleOverviews = $this->saleService->createForOrder(
                $order
            );

            try {

                /**
                 * Creating sale overview logs
                 */
                $this->saleService->addTransactionLogs(
                    $saleOverviews,
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

        return $this->respondWithSuccess([],
            trans('validations/api/general/cart/checkoutExecute.result.success')
        );
    }

    /**
     * @param CheckoutCancelRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     */
    public function checkoutCancel(
        CheckoutCancelRequest $request
    ) : JsonResponse
    {
        /**
         * Getting order
         */
        $order = $this->orderRepository->findFullById(
            $request->input('order_id')
        );

        /**
         * Checking order existence
         */
        if (!$order) {
            return $this->respondWithError(
                trans('validations/api/general/cart/checkoutCancel.result.error.order.find')
            );
        }

        /**
         * Checking user is an owner
         */
        if (!AuthService::user()->is(
            $order->buyer
        )) {
            return $this->respondWithError(
                trans('validations/api/general/cart/checkoutCancel.result.error.owner')
            );
        }

        /**
         * Checking order hash
         */
        if (!$this->orderService->checkHash(
            $order,
            $request->input('hash')
        )) {
            return $this->respondWithError(
                trans('validations/api/general/cart/checkoutCancel.result.error.hash')
            );
        }

        /**
         * Checking order invoice existence
         */
        if (!$order->getBuyerInvoice()) {
            return $this->respondWithError(
                trans('validations/api/general/cart/checkoutCancel.result.error.orderInvoice.find')
            );
        }

        /**
         * Checking order invoice status
         */
        if (!$order->getBuyerInvoice()
            ->getStatus()
            ->isUnpaid()
        ) {
            return $this->respondWithError(
                trans('validations/api/general/cart/checkoutCancel.result.error.orderInvoice.status')
            );
        }

        /**
         * Canceling payment by method
         */
        $order = $this->orderService->cancelPayment(
            $order
        );

        /**
         * Checking buyer invoice status
         */
        if ($order->getBuyerInvoice()
            ->getStatus()
            ->isCanceled()
        ) {

            try {

                /**
                 * Creating order invoice for seller logs
                 */
                $this->logService->addInvoiceForBuyerLog(
                    $order->getBuyerInvoice(),
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

        return $this->respondWithSuccess([],
            trans('validations/api/general/cart/checkoutCancel.result.success')
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
         * Getting cart item
         */
        $cartItem = $this->cartItemRepository->findById($id);

        /**
         * Checking cart item existence
         */
        if (!$cartItem) {
            return $this->respondWithError(
                trans('validations/api/general/cart/destroy.result.error.find')
            );
        }

        /**
         * Deleting cart item
         */
        $this->cartItemRepository->delete(
            $cartItem
        );

        return $this->respondWithSuccess([],
            trans('validations/api/general/cart/destroy.result.success')
        );
    }
}
