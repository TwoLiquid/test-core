<?php

namespace App\Http\Controllers\Api\Admin\Order;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Admin\Order\Interfaces\OrderItemRescheduleRequestControllerInterface;
use App\Http\Requests\Api\Admin\Order\OrderItem\RescheduleRequest\IndexRequest;
use App\Repositories\Order\OrderItemRescheduleRequestRepository;
use App\Services\Order\OrderItemRescheduleRequestService;
use App\Transformers\Api\Admin\Order\OrderItem\RescheduleRequest\OrderItemRescheduleRequestListPageTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class OrderItemRescheduleRequestController
 *
 * @package App\Http\Controllers\Api\Admin\Order
 */
class OrderItemRescheduleRequestController extends BaseController implements OrderItemRescheduleRequestControllerInterface
{
    /**
     * @var OrderItemRescheduleRequestRepository
     */
    protected OrderItemRescheduleRequestRepository $orderItemRescheduleRequestRepository;

    /**
     * @var OrderItemRescheduleRequestService
     */
    protected OrderItemRescheduleRequestService $orderItemRescheduleRequestService;

    /**
     * OrderItemRescheduleRequestController constructor
     */
    public function __construct()
    {
        /** @var OrderItemRescheduleRequestRepository orderItemRescheduleRequestRepository */
        $this->orderItemRescheduleRequestRepository = new OrderItemRescheduleRequestRepository();

        /** @var OrderItemRescheduleRequestService orderItemRescheduleRequestService */
        $this->orderItemRescheduleRequestService = new OrderItemRescheduleRequestService();
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
         * Getting order item reschedule requests with pagination
         */
        $orderItemRescheduleRequests = $this->orderItemRescheduleRequestRepository->getAllFiltered(
            $request->input('order_item_id'),
            $request->input('from_request_datetime'),
            $request->input('to_request_datetime'),
            $request->input('vybe_title'),
            $request->input('order_date_from'),
            $request->input('order_date_to'),
            $request->input('buyer'),
            $request->input('seller'),
            $request->input('from_order_item_statuses_ids'),
            $request->input('to_order_item_statuses_ids'),
            $request->input('order_item_request_action_ids'),
            $request->input('sort_by'),
            $request->input('sort_order')
        );

        /**
         * Getting order item reschedule requests for labels
         */
        $orderItemRescheduleRequestsForLabels = $this->orderItemRescheduleRequestRepository->getAllFilteredForAdminLabels(
            $request->input('order_item_id'),
            $request->input('from_request_datetime'),
            $request->input('to_request_datetime'),
            $request->input('vybe_title'),
            $request->input('order_date_from'),
            $request->input('order_date_to'),
            $request->input('buyer'),
            $request->input('seller')
        );

        /**
         * Getting order item reschedule request statuses with counts
         */
        $requestStatuses = $this->orderItemRescheduleRequestService->getForAdminStatusesWithCounts(
            $orderItemRescheduleRequestsForLabels
        );

        /**
         * Getting order item reschedule request order item statuses with counts
         */
        $toOrderItemStatuses = $this->orderItemRescheduleRequestService->getForAdminToOrderItemStatusesWithCounts(
            $orderItemRescheduleRequestsForLabels
        );

        /**
         * Checking pagination existence
         */
        if ($request->input('paginated') === true) {

            /**
             * Paginating order item reschedule requests
             */
            $paginatedOrderItemRescheduleRequests = paginateCollection(
                $orderItemRescheduleRequests,
                $request->input('per_page') ?
                    $request->input('per_page') :
                    $this->orderItemRescheduleRequestRepository->getPerPage()
            );

            return $this->setPagination($paginatedOrderItemRescheduleRequests)->respondWithSuccess(
                $this->transformItem([],
                    new OrderItemRescheduleRequestListPageTransformer(
                        new Collection($paginatedOrderItemRescheduleRequests->items()),
                        $requestStatuses,
                        $toOrderItemStatuses
                    )
                )['order_item_reschedule_request_list'],
                trans('validations/api/admin/order/orderItem/rescheduleRequest/index.result.success')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem([],
                new OrderItemRescheduleRequestListPageTransformer(
                    $orderItemRescheduleRequests,
                    $requestStatuses,
                    $toOrderItemStatuses
                )
            )['order_item_reschedule_request_list'],
            trans('validations/api/admin/order/orderItem/rescheduleRequest/index.result.success')
        );
    }
}
