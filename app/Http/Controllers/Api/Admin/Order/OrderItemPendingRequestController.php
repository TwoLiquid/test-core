<?php

namespace App\Http\Controllers\Api\Admin\Order;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Admin\Order\Interfaces\OrderItemPendingRequestControllerInterface;
use App\Http\Requests\Api\Admin\Order\OrderItem\PendingRequest\IndexRequest;
use App\Repositories\Order\OrderItemPendingRequestRepository;
use App\Services\Order\OrderItemPendingRequestService;
use App\Transformers\Api\Admin\Order\OrderItem\PendingRequest\OrderItemPendingRequestListPageTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class OrderItemPendingRequestController
 *
 * @package App\Http\Controllers\Api\Admin\Order
 */
class OrderItemPendingRequestController extends BaseController implements OrderItemPendingRequestControllerInterface
{
    /**
     * @var OrderItemPendingRequestRepository
     */
    protected OrderItemPendingRequestRepository $orderItemPendingRequestRepository;

    /**
     * @var OrderItemPendingRequestService
     */
    protected OrderItemPendingRequestService $orderItemPendingRequestService;

    /**
     * OrderItemPendingRequestController constructor
     */
    public function __construct()
    {
        /** @var OrderItemPendingRequestRepository orderItemPendingRequestRepository */
        $this->orderItemPendingRequestRepository = new OrderItemPendingRequestRepository();

        /** @var OrderItemPendingRequestService orderItemPendingRequestService */
        $this->orderItemPendingRequestService = new OrderItemPendingRequestService();
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
         * Getting order item pending requests with pagination
         */
        $orderItemPendingRequests = $this->orderItemPendingRequestRepository->getAllFiltered(
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
         * Getting order item pending requests for labels
         */
        $orderItemPendingRequestsForLabels = $this->orderItemPendingRequestRepository->getAllFilteredForAdminLabels(
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
         * Getting order item pending request statuses with counts
         */
        $requestStatuses = $this->orderItemPendingRequestService->getForAdminStatusesWithCounts(
            $orderItemPendingRequestsForLabels
        );

        /**
         * Getting order item pending request order item statuses with counts
         */
        $toOrderItemStatuses = $this->orderItemPendingRequestService->getForAdminToOrderItemStatusesWithCounts(
            $orderItemPendingRequestsForLabels
        );

        /**
         * Checking pagination existence
         */
        if ($request->input('paginated') === true) {

            /**
             * Paginating order item pending requests
             */
            $paginatedOrderItemPendingRequests = paginateCollection(
                $orderItemPendingRequests,
                $request->input('per_page') ?
                    $request->input('per_page') :
                    $this->orderItemPendingRequestRepository->getPerPage()
            );

            return $this->setPagination($paginatedOrderItemPendingRequests)->respondWithSuccess(
                $this->transformItem([],
                    new OrderItemPendingRequestListPageTransformer(
                        new Collection($paginatedOrderItemPendingRequests->items()),
                        $requestStatuses,
                        $toOrderItemStatuses
                    )
                )['order_item_pending_request_list'],
                trans('validations/api/admin/order/orderItem/pendingRequest/index.result.success')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem([],
                new OrderItemPendingRequestListPageTransformer(
                    $orderItemPendingRequests,
                    $requestStatuses,
                    $toOrderItemStatuses
                )
            )['order_item_pending_request_list'],
            trans('validations/api/admin/order/orderItem/pendingRequest/index.result.success')
        );
    }
}
