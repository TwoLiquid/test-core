<?php

namespace App\Http\Controllers\Api\General\Dashboard\Sale;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Exceptions\LogException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\General\Dashboard\Sale\Interfaces\SaleControllerInterface;
use App\Http\Requests\Api\General\Dashboard\Sale\IndexRequest;
use App\Http\Requests\Api\General\Dashboard\Sale\RescheduleRequest;
use App\Lists\Order\Item\Payment\Status\OrderItemPaymentStatusList;
use App\Lists\Order\Item\Purchase\SortBy\OrderItemPurchaseSortByList;
use App\Lists\Order\Item\Request\Action\OrderItemRequestActionList;
use App\Lists\Order\Item\Request\Initiator\OrderItemRequestInitiatorList;
use App\Lists\Order\Item\Status\OrderItemStatusList;
use App\Lists\User\Balance\Type\UserBalanceTypeList;
use App\Lists\Vybe\Appearance\VybeAppearanceList;
use App\Lists\Vybe\Type\VybeTypeList;
use App\Repositories\Activity\ActivityRepository;
use App\Repositories\Media\ActivityImageRepository;
use App\Repositories\Media\UserAvatarRepository;
use App\Repositories\Order\OrderItemFinishRequestRepository;
use App\Repositories\Order\OrderItemRepository;
use App\Repositories\Order\OrderItemRescheduleRequestRepository;
use App\Repositories\Timeslot\TimeslotRepository;
use App\Repositories\User\UserBalanceRepository;
use App\Services\Activity\ActivityService;
use App\Services\Auth\AuthService;
use App\Services\Invoice\InvoiceService;
use App\Services\Log\LogService;
use App\Services\Order\OrderItemPendingRequestService;
use App\Services\User\UserService;
use App\Transformers\Api\General\Dashboard\Sale\OrderItemTransformer;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class SaleController
 *
 * @package App\Http\Controllers\Api\General\Dashboard\Sale
 */
final class SaleController extends BaseController implements SaleControllerInterface
{
    /**
     * @var ActivityRepository
     */
    protected ActivityRepository $activityRepository;

    /**
     * @var ActivityService
     */
    protected ActivityService $activityService;

    /**
     * @var ActivityImageRepository
     */
    protected ActivityImageRepository $activityImageRepository;

    /**
     * @var InvoiceService
     */
    protected InvoiceService $invoiceService;

    /**
     * @var LogService
     */
    protected LogService $logService;

    /**
     * @var OrderItemRepository
     */
    protected OrderItemRepository $orderItemRepository;

    /**
     * @var OrderItemPendingRequestService
     */
    protected OrderItemPendingRequestService $orderItemPendingRequestService;

    /**
     * @var OrderItemRescheduleRequestRepository
     */
    protected OrderItemRescheduleRequestRepository $orderItemRescheduleRequestRepository;

    /**
     * @var OrderItemFinishRequestRepository
     */
    protected OrderItemFinishRequestRepository $orderItemFinishRequestRepository;

    /**
     * @var TimeslotRepository
     */
    protected TimeslotRepository $timeslotRepository;

    /**
     * @var UserAvatarRepository
     */
    protected UserAvatarRepository $userAvatarRepository;

    /**
     * @var UserBalanceRepository
     */
    protected UserBalanceRepository $userBalanceRepository;

    /**
     * @var UserService
     */
    protected UserService $userService;

    /**
     * SaleController constructor
     */
    public function __construct()
    {
        /** @var ActivityRepository activityRepository */
        $this->activityRepository = new ActivityRepository();

        /** @var ActivityService activityService */
        $this->activityService = new ActivityService();

        /** @var ActivityImageRepository activityImageRepository */
        $this->activityImageRepository = new ActivityImageRepository();

        /** @var InvoiceService invoiceService */
        $this->invoiceService = new InvoiceService();

        /** @var LogService logService */
        $this->logService = new LogService();

        /** @var OrderItemRepository orderItemRepository */
        $this->orderItemRepository = new OrderItemRepository();

        /** @var OrderItemPendingRequestService orderItemPendingRequestService */
        $this->orderItemPendingRequestService = new OrderItemPendingRequestService();

        /** @var OrderItemRescheduleRequestRepository orderItemRescheduleRequestRepository */
        $this->orderItemRescheduleRequestRepository = new OrderItemRescheduleRequestRepository();

        /** @var OrderItemFinishRequestRepository orderItemFinishRequestRepository */
        $this->orderItemFinishRequestRepository = new OrderItemFinishRequestRepository();

        /** @var TimeslotRepository timeslotRepository */
        $this->timeslotRepository = new TimeslotRepository();

        /** @var UserAvatarRepository userAvatarRepository */
        $this->userAvatarRepository = new UserAvatarRepository();

        /** @var UserBalanceRepository userBalanceRepository */
        $this->userBalanceRepository = new UserBalanceRepository();

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
         * Getting vybe appearance
         */
        $vybeAppearanceListItem = VybeAppearanceList::getItem(
            $request->input('appearance_id')
        );

        /**
         * Getting vybe type
         */
        $vybeTypeListItem = VybeTypeList::getItem(
            $request->input('type_id')
        );

        /**
         * Getting activity
         */
        $activity = $this->activityRepository->findById(
            $request->input('activity_id')
        );

        /**
         * Getting order item status
         */
        $orderItemStatusListItem = OrderItemStatusList::getItem(
            $request->input('order_item_status_id')
        );

        /**
         * Getting item purchase sort by
         */
        $orderItemPurchaseSortByListItem = OrderItemPurchaseSortByList::getItem(
            $request->input('order_item_sale_sort_by_id')
        );

        /**
         * Getting order items
         */
        $orderItems = $this->orderItemRepository->getAllSalesPaginatedFiltered(
            AuthService::user(),
            $vybeAppearanceListItem,
            $vybeTypeListItem,
            $activity,
            $orderItemStatusListItem,
            $orderItemPurchaseSortByListItem,
            $request->input('vybe_title'),
            $request->input('username'),
            $request->input('datetime_from'),
            $request->input('datetime_to'),
            $request->input('amount_from'),
            $request->input('amount_to'),
            $request->input('quantity'),
            $request->input('only_open'),
            $request->input('sort_order'),
            $request->input('per_page'),
            $request->input('page')
        );

        return $this->respondWithSuccess(
            $this->transformCollection($orderItems, new OrderItemTransformer(
                $this->activityImageRepository->getByActivities(
                    $this->activityService->getByOrderItems(
                        new Collection($orderItems->items())
                    )
                ),
                $this->userAvatarRepository->getByUsers(
                    $this->userService->getByOrderItems(
                        new Collection($orderItems->items())
                    )
                )
            )), trans('validations/api/general/dashboard/sale/index.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function acceptOrder(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting order item error
         */
        $orderItem = $this->orderItemRepository->findFullById($id);

        /**
         * Getting order item error existence
         */
        if (!$orderItem) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/sale/acceptOrder.result.error.find')
            );
        }

        /**
         * Checking user is seller
         */
        if (!AuthService::user()->is(
            $orderItem->seller
        )) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/sale/acceptOrder.result.error.seller')
            );
        }

        /**
         * Checking order item status
         */
        if (!$orderItem->getStatus()->isPending()) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/sale/acceptOrder.result.error.status')
            );
        }

        /**
         * Updating order item previous status
         */
        $orderItem = $this->orderItemRepository->updatePreviousStatus(
            $orderItem,
            $orderItem->getStatus()
        );

        /**
         * Updating order item status
         */
        $orderItem = $this->orderItemRepository->updateStatus(
            $orderItem,
            OrderItemStatusList::getInProcess()
        );

        /**
         * Checking opened order item pending request existence
         */
        if ($orderItem->getOpenedPendingRequest()) {

            /**
             * Updating order item pending request
             */
            $this->orderItemPendingRequestService->close(
                $orderItem->seller,
                $orderItem->getOpenedPendingRequest(),
                $orderItem->getStatus(),
                OrderItemRequestActionList::getAccepted()
            );
        }

        /**
         * Getting order item
         */
        $orderItem = $this->orderItemRepository->findFullById(
            $orderItem->id
        );

        return $this->respondWithSuccess(
            $this->transformItem($orderItem, new OrderItemTransformer(
                $this->activityImageRepository->getByActivities(
                    $this->activityService->getByOrderItems(
                        new Collection([$orderItem])
                    )
                ),
                $this->userAvatarRepository->getByUsers(
                    $this->userService->getByOrderItems(
                        new Collection([$orderItem])
                    )
                )
            )), trans('validations/api/general/dashboard/sale/acceptOrder.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws BaseException
     * @throws DatabaseException
     * @throws GuzzleException
     */
    public function declineOrder(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting order item error
         */
        $orderItem = $this->orderItemRepository->findFullById($id);

        /**
         * Getting order item error existence
         */
        if (!$orderItem) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/sale/declineOrder.result.error.find')
            );
        }

        /**
         * Checking user is seller
         */
        if (!AuthService::user()->is(
            $orderItem->seller
        )) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/sale/declineOrder.result.error.seller')
            );
        }

        /**
         * Checking order item status
         */
        if (!$orderItem->getStatus()->isPending()) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/sale/declineOrder.result.error.status')
            );
        }

        /**
         * Creating order invoice
         */
        $creditBuyerInvoice = $this->invoiceService->createCreditForBuyer(
            $orderItem->order->getBuyerInvoice(),
            $orderItem
        );

        try {

            /**
             * Creating credit invoice for buyer logs
             */
            $this->logService->addCreditInvoiceForBuyerLog(
                $creditBuyerInvoice,
                $orderItem->order
                    ->buyer
                    ->getBuyerBalance(),
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
         * Updating buyer balance
         */
        $this->userBalanceRepository->increaseAmount(
            $orderItem->order
                ->buyer
                ->getBuyerBalance(),
            $orderItem->amount_total
        );

        /**
         * Updating seller pending balance
         */
        $this->userBalanceRepository->decreasePendingAmount(
            $orderItem->seller
                ->getSellerBalance(),
            $orderItem->amount_earned * $orderItem->quantity
        );

        //TODO: Create seller pending balance transaction logs

        /**
         * Updating order item statuses
         */
        $this->orderItemRepository->updateStatuses(
            $orderItem,
            OrderItemStatusList::getCanceled(),
            OrderItemPaymentStatusList::getPaidPartialRefund()
        );

        /**
         * Update order item previous status
         */
        $this->orderItemRepository->updatePreviousStatus(
            $orderItem,
            OrderItemStatusList::getPending()
        );

        /**
         * Checking opened order item pending request existence
         */
        if ($orderItem->getOpenedPendingRequest()) {

            /**
             * Updating order item pending request
             */
            $this->orderItemPendingRequestService->close(
                $orderItem->seller,
                $orderItem->getOpenedPendingRequest(),
                $orderItem->getStatus(),
                OrderItemRequestActionList::getCanceled()
            );
        }

        /**
         * Getting order item
         */
        $orderItem = $this->orderItemRepository->findFullById(
            $orderItem->id
        );

        return $this->respondWithSuccess(
            $this->transformItem($orderItem, new OrderItemTransformer(
                $this->activityImageRepository->getByActivities(
                    $this->activityService->getByOrderItems(
                        new Collection([$orderItem])
                    )
                ),
                $this->userAvatarRepository->getByUsers(
                    $this->userService->getByOrderItems(
                        new Collection([$orderItem])
                    )
                )
            )), trans('validations/api/general/dashboard/sale/declineOrder.result.success')
        );
    }

    /**
     * @param int $id
     * @param RescheduleRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function reschedule(
        int $id,
        RescheduleRequest $request
    ) : JsonResponse
    {
        /**
         * Getting order item error
         */
        $orderItem = $this->orderItemRepository->findFullById($id);

        /**
         * Getting order item error existence
         */
        if (!$orderItem) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/sale/reschedule.result.error.find')
            );
        }

        /**
         * Checking user is seller
         */
        if (!AuthService::user()->is(
            $orderItem->seller
        )) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/sale/reschedule.result.error.seller')
            );
        }

        /**
         * Checking order item status
         */
        if (!$orderItem->getStatus()->isPending() &&
            !$orderItem->getStatus()->isInProcess()
        ) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/sale/reschedule.result.error.status')
            );
        }

        //TODO: Improve checking timeslot logic

        /**
         * Getting timeslot
         */
        $timeslot = $this->timeslotRepository->findForVybeBetweenDates(
            $orderItem->vybe,
            $request->input('datetime_from'),
            $request->input('datetime_to')
        );

        /**
         * Checking timeslot existence
         */
        if ($timeslot) {

            /**
             * Checking order item vybe type
             */
            if ($orderItem->vybe->getType()->isSolo()) {
                return $this->respondWithError(
                    trans('validations/api/general/dashboard/sale/reschedule.result.error.timeslot.busy')
                );
            } else {

                /**
                 * Checking group / event timeslot users count
                 */
                if ($timeslot->users_count >= $orderItem->vybe->user_count) {
                    return $this->respondWithError(
                        trans('validations/api/general/dashboard/sale/reschedule.result.error.timeslot.full')
                    );
                }
            }
        }

        /**
         * Create order item reschedule request
         */
        $orderItemRescheduleRequest = $this->orderItemRescheduleRequestRepository->store(
            $orderItem,
            $orderItem->order->buyer,
            $orderItem->seller,
            $orderItem->order->buyer,
            $request->input('datetime_from'),
            $request->input('datetime_to'),
            OrderItemRequestInitiatorList::getBuyer(),
            $orderItem->getStatus()
        );

        /**
         * Checking order item reschedule request existence
         */
        if (!$orderItemRescheduleRequest) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/purchase/sale.result.error.orderItemRescheduleRequest.create')
            );
        }

        /**
         * Updating order item previous status
         */
        $this->orderItemRepository->updatePreviousStatus(
            $orderItem,
            $orderItem->getStatus()
        );

        /**
         * Updating order item status
         */
        $this->orderItemRepository->updateStatus(
            $orderItem,
            OrderItemStatusList::getReschedule()
        );

        /**
         * Getting order item
         */
        $orderItem = $this->orderItemRepository->findFullById(
            $orderItem->id
        );

        return $this->respondWithSuccess(
            $this->transformItem($orderItem, new OrderItemTransformer(
                $this->activityImageRepository->getByActivities(
                    $this->activityService->getByOrderItems(
                        new Collection([$orderItem])
                    )
                ),
                $this->userAvatarRepository->getByUsers(
                    $this->userService->getByOrderItems(
                        new Collection([$orderItem])
                    )
                )
            )), trans('validations/api/general/dashboard/sale/reschedule.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function acceptRescheduleRequest(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting order item error
         */
        $orderItem = $this->orderItemRepository->findFullById($id);

        /**
         * Getting order item error existence
         */
        if (!$orderItem) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/sale/acceptRescheduleRequest.result.error.find')
            );
        }

        /**
         * Checking user is seller
         */
        if (!AuthService::user()->is(
            $orderItem->seller
        )) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/sale/acceptRescheduleRequest.result.error.seller')
            );
        }

        /**
         * Checking order item status
         */
        if (!$orderItem->getStatus()->isReschedule()) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/sale/acceptRescheduleRequest.result.error.status')
            );
        }

        /**
         * Checking order item reschedule request existence
         */
        if (!$orderItem->getOpenedRescheduleRequest()) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/sale/acceptRescheduleRequest.result.error.rescheduleRequest.find')
            );
        }

        /**
         * Checking order item reschedule request opening
         */
        if ($orderItem->getOpenedRescheduleRequest()
            ->opening
            ->is(AuthService::user())
        ) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/sale/acceptRescheduleRequest.result.error.rescheduleRequest.opening')
            );
        }

        //TODO: Improve checking timeslot logic

        /**
         * Getting timeslot
         */
        $timeslot = $this->timeslotRepository->findForVybeBetweenDates(
            $orderItem->vybe,
            $orderItem->getOpenedRescheduleRequest()->datetime_from,
            $orderItem->getOpenedRescheduleRequest()->datetime_to
        );

        /**
         * Checking timeslot existence
         */
        if ($timeslot) {

            /**
             * Checking order item vybe type
             */
            if ($orderItem->vybe->getType()->isSolo()) {
                return $this->respondWithError(
                    trans('validations/api/general/dashboard/sale/acceptRescheduleRequest.result.error.timeslot.busy')
                );
            } else {

                /**
                 * Checking group / event timeslot users count
                 */
                if ($timeslot->users_count >= $orderItem->vybe->user_count) {
                    return $this->respondWithError(
                        trans('validations/api/general/dashboard/sale/acceptRescheduleRequest.result.error.timeslot.full')
                    );
                }
            }
        } else {

            /**
             * Creating timeslot
             */
            $timeslot = $this->timeslotRepository->store(
                $orderItem->getOpenedRescheduleRequest()->datetime_from,
                $orderItem->getOpenedRescheduleRequest()->datetime_to
            );

            /**
             * Checking timeslot existence
             */
            if (!$timeslot) {
                return $this->respondWithError(
                    trans('validations/api/general/dashboard/sale/acceptRescheduleRequest.result.error.timeslot.create')
                );
            }
        }

        /**
         * Deleting timeslot
         */
        $this->timeslotRepository->delete(
            $orderItem->timeslot
        );

        /**
         * Updating order item
         */
        $this->orderItemRepository->updateTimeslot(
            $orderItem,
            $timeslot
        );

        /**
         * Updating order item reschedule request
         */
        $this->orderItemRescheduleRequestRepository->updateWhenClose(
            $orderItem->getOpenedRescheduleRequest(),
            $orderItem->order->buyer,
            OrderItemStatusList::getInProcess(),
            OrderItemRequestActionList::getAccepted()
        );

        /**
         * Updating order item previous status
         */
        $this->orderItemRepository->updatePreviousStatus(
            $orderItem,
            $orderItem->getStatus()
        );

        /**
         * Updating order item status
         */
        $this->orderItemRepository->updateStatus(
            $orderItem,
            OrderItemStatusList::getInProcess()
        );

        /**
         * Getting order item
         */
        $orderItem = $this->orderItemRepository->findFullById(
            $orderItem->id
        );

        return $this->respondWithSuccess(
            $this->transformItem($orderItem, new OrderItemTransformer(
                $this->activityImageRepository->getByActivities(
                    $this->activityService->getByOrderItems(
                        new Collection([$orderItem])
                    )
                ),
                $this->userAvatarRepository->getByUsers(
                    $this->userService->getByOrderItems(
                        new Collection([$orderItem])
                    )
                )
            )), trans('validations/api/general/dashboard/sale/acceptRescheduleRequest.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function declineRescheduleRequest(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting order item error
         */
        $orderItem = $this->orderItemRepository->findFullById($id);

        /**
         * Getting order item error existence
         */
        if (!$orderItem) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/sale/declineRescheduleRequest.result.error.find')
            );
        }

        /**
         * Checking user is seller
         */
        if (!AuthService::user()->is(
            $orderItem->seller
        )) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/sale/declineRescheduleRequest.result.error.seller')
            );
        }

        /**
         * Checking order item status
         */
        if (!$orderItem->getStatus()->isReschedule()) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/sale/declineRescheduleRequest.result.error.status')
            );
        }

        /**
         * Checking order item reschedule request existence
         */
        if (!$orderItem->getOpenedRescheduleRequest()) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/sale/declineRescheduleRequest.result.error.rescheduleRequest.find')
            );
        }

        /**
         * Checking order item reschedule request opening
         */
        if ($orderItem->getOpenedRescheduleRequest()
            ->opening
            ->is(AuthService::user())
        ) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/sale/declineRescheduleRequest.result.error.rescheduleRequest.opening')
            );
        }

        /**
         * Updating order item reschedule request
         */
        $this->orderItemRescheduleRequestRepository->updateWhenClose(
            $orderItem->getOpenedRescheduleRequest(),
            $orderItem->order->buyer,
            OrderItemStatusList::getInProcess(),
            OrderItemRequestActionList::getDeclined()
        );

        /**
         * Updating order item previous status
         */
        $this->orderItemRepository->updatePreviousStatus(
            $orderItem,
            $orderItem->getStatus()
        );

        /**
         * Updating order item status
         */
        $this->orderItemRepository->updateStatus(
            $orderItem,
            OrderItemStatusList::getInProcess()
        );

        /**
         * Getting order item
         */
        $orderItem = $this->orderItemRepository->findFullById(
            $orderItem->id
        );

        return $this->respondWithSuccess(
            $this->transformItem($orderItem, new OrderItemTransformer(
                $this->activityImageRepository->getByActivities(
                    $this->activityService->getByOrderItems(
                        new Collection([$orderItem])
                    )
                ),
                $this->userAvatarRepository->getByUsers(
                    $this->userService->getByOrderItems(
                        new Collection([$orderItem])
                    )
                )
            )), trans('validations/api/general/dashboard/sale/declineRescheduleRequest.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function cancelRescheduleRequest(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting order item error
         */
        $orderItem = $this->orderItemRepository->findFullById($id);

        /**
         * Getting order item error existence
         */
        if (!$orderItem) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/sale/cancelRescheduleRequest.result.error.find')
            );
        }

        /**
         * Checking user is seller
         */
        if (!AuthService::user()->is(
            $orderItem->seller
        )) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/sale/cancelRescheduleRequest.result.error.seller')
            );
        }

        /**
         * Checking order item status
         */
        if (!$orderItem->getStatus()->isReschedule()) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/sale/cancelRescheduleRequest.result.error.status')
            );
        }

        /**
         * Checking order item reschedule request existence
         */
        if (!$orderItem->getOpenedRescheduleRequest()) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/sale/cancelRescheduleRequest.result.error.rescheduleRequest.find')
            );
        }

        /**
         * Checking order item reschedule request opening
         */
        if (!$orderItem->getOpenedRescheduleRequest()
            ->opening
            ->is(AuthService::user())
        ) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/sale/cancelRescheduleRequest.result.error.rescheduleRequest.opening')
            );
        }

        /**
         * Updating order item reschedule request
         */
        $this->orderItemRescheduleRequestRepository->updateWhenClose(
            $orderItem->getOpenedRescheduleRequest(),
            $orderItem->order->buyer,
            OrderItemStatusList::getInProcess(),
            OrderItemRequestActionList::getCanceled()
        );

        /**
         * Updating order item previous status
         */
        $this->orderItemRepository->updatePreviousStatus(
            $orderItem,
            $orderItem->getStatus()
        );

        /**
         * Updating order item status
         */
        $this->orderItemRepository->updateStatus(
            $orderItem,
            OrderItemStatusList::getInProcess()
        );

        /**
         * Getting order item
         */
        $orderItem = $this->orderItemRepository->findFullById(
            $orderItem->id
        );

        return $this->respondWithSuccess(
            $this->transformItem($orderItem, new OrderItemTransformer(
                $this->activityImageRepository->getByActivities(
                    $this->activityService->getByOrderItems(
                        new Collection([$orderItem])
                    )
                ),
                $this->userAvatarRepository->getByUsers(
                    $this->userService->getByOrderItems(
                        new Collection([$orderItem])
                    )
                )
            )), trans('validations/api/general/dashboard/sale/cancelRescheduleRequest.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function markAsFinished(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting order item error
         */
        $orderItem = $this->orderItemRepository->findFullById($id);

        /**
         * Getting order item error existence
         */
        if (!$orderItem) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/sale/markAsFinished.result.error.find')
            );
        }

        /**
         * Checking user is seller
         */
        if (!AuthService::user()->is(
            $orderItem->seller
        )) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/sale/markAsFinished.result.error.seller')
            );
        }

        /**
         * Checking order item status
         */
        if (!$orderItem->getStatus()->isInProcess()) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/sale/markAsFinished.result.error.status')
            );
        }

        /**
         * Checking opened order item finish request existence
         */
        if ($orderItem->getOpenedFinishRequest()) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/sale/markAsFinished.result.error.finishRequest')
            );
        }

        /**
         * Creating order item finish request
         */
        $this->orderItemFinishRequestRepository->store(
            $orderItem,
            $orderItem->order->buyer,
            $orderItem->seller,
            $orderItem->seller,
            OrderItemRequestInitiatorList::getSeller(),
            OrderItemStatusList::getPending()
        );

        /**
         * Updating order item previous status
         */
        $this->orderItemRepository->updatePreviousStatus(
            $orderItem,
            $orderItem->getStatus()
        );

        /**
         * Updating order item status
         */
        $this->orderItemRepository->updateStatus(
            $orderItem,
            OrderItemStatusList::getFinishRequest()
        );

        return $this->respondWithSuccess(
            $this->transformItem($orderItem, new OrderItemTransformer(
                $this->activityImageRepository->getByActivities(
                    $this->activityService->getByOrderItems(
                        new Collection([$orderItem])
                    )
                ),
                $this->userAvatarRepository->getByUsers(
                    $this->userService->getByOrderItems(
                        new Collection([$orderItem])
                    )
                )
            )), trans('validations/api/general/dashboard/sale/markAsFinished.result.success')
        );
    }

//    /**
//     * @param int $id
//     *
//     * @return JsonResponse
//     *
//     * @throws DatabaseException
//     */
//    public function openDispute(
//        int $id
//    ) : JsonResponse
//    {
//        /**
//         * Getting order item error
//         */
//        $orderItem = $this->orderItemRepository->findFullById($id);
//
//        /**
//         * Getting order item error existence
//         */
//        if (!$orderItem) {
//            return $this->respondWithError(
//                trans('validations/api/general/dashboard/sale/openDispute.result.error.find')
//            );
//        }
//
//        /**
//         * Checking order item status
//         */
//        if (!$orderItem->getStatus()->isInProcess()) {
//            return $this->respondWithError(
//                trans('validations/api/general/dashboard/sale/openDispute.result.error.status')
//            );
//        }
//
//        /**
//         * Updating order item status
//         */
//        $this->orderItemRepository->updateStatus(
//            $orderItem,
//            OrderItemStatusList::getDisputed()
//        );
//
//        return $this->respondWithSuccess([],
//            trans('validations/api/general/dashboard/sale/openDispute.result.success')
//        );
//    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function cancelFinishRequest(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting order item error
         */
        $orderItem = $this->orderItemRepository->findFullById($id);

        /**
         * Getting order item error existence
         */
        if (!$orderItem) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/sale/cancelFinishRequest.result.error.find')
            );
        }

        /**
         * Checking user is seller
         */
        if (!AuthService::user()->is(
            $orderItem->seller
        )) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/sale/cancelFinishRequest.result.error.seller')
            );
        }

        /**
         * Checking order item status
         */
        if (!$orderItem->getStatus()->isFinishRequest()) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/sale/cancelFinishRequest.result.error.status')
            );
        }

        /**
         * Checking opened order item finish request existence
         */
        if (!$orderItem->getOpenedFinishRequest()) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/sale/cancelFinishRequest.result.error.finishRequest')
            );
        }

        /**
         * Updating order item finish request
         */
        $this->orderItemFinishRequestRepository->updateWhenClose(
            $orderItem->getOpenedFinishRequest(),
            $orderItem->seller,
            OrderItemStatusList::getInProcess(),
            OrderItemRequestActionList::getCanceled()
        );

        /**
         * Updating order item previous status
         */
        $this->orderItemRepository->updatePreviousStatus(
            $orderItem,
            $orderItem->getStatus()
        );

        /**
         * Updating order item status
         */
        $this->orderItemRepository->updateStatus(
            $orderItem,
            OrderItemStatusList::getInProcess()
        );

        return $this->respondWithSuccess(
            $this->transformItem($orderItem, new OrderItemTransformer(
                $this->activityImageRepository->getByActivities(
                    $this->activityService->getByOrderItems(
                        new Collection([$orderItem])
                    )
                ),
                $this->userAvatarRepository->getByUsers(
                    $this->userService->getByOrderItems(
                        new Collection([$orderItem])
                    )
                )
            )), trans('validations/api/general/dashboard/sale/cancelFinishRequest.result.success')
        );
    }
}
