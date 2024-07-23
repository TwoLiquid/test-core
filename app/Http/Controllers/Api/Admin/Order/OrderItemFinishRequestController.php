<?php

namespace App\Http\Controllers\Api\Admin\Order;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Admin\Order\Interfaces\OrderItemFinishRequestControllerInterface;
use App\Http\Requests\Api\Admin\Order\OrderItem\FinishRequest\IndexRequest;
use App\Repositories\Order\OrderItemFinishRequestRepository;
use App\Services\Order\OrderItemFinishRequestService;
use App\Transformers\Api\Admin\Order\OrderItem\FinishRequest\OrderItemFinishRequestListPageTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class OrderItemFinishRequestController
 *
 * @package App\Http\Controllers\Api\Admin\Order
 */
class OrderItemFinishRequestController extends BaseController implements OrderItemFinishRequestControllerInterface
{
    /**
     * @var OrderItemFinishRequestRepository 
     */
    protected OrderItemFinishRequestRepository $orderItemFinishRequestRepository;

    /**
     * @var OrderItemFinishRequestService
     */
    protected OrderItemFinishRequestService $orderItemFinishRequestService;

    /**
     * OrderItemFinishRequestController constructor
     */
    public function __construct()
    {
        /** @var OrderItemFinishRequestRepository orderItemFinishRequestRepository */
        $this->orderItemFinishRequestRepository = new OrderItemFinishRequestRepository();

        /** @var OrderItemFinishRequestService orderItemFinishRequestService */
        $this->orderItemFinishRequestService = new OrderItemFinishRequestService();
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
         * Getting order item finish requests with pagination
         */
        $orderItemFinishRequests = $this->orderItemFinishRequestRepository->getAllFiltered(
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
         * Getting order item finish requests for labels
         */
        $orderItemFinishRequestsForLabels = $this->orderItemFinishRequestRepository->getAllFilteredForAdminLabels(
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
         * Getting order item finish request statuses with counts
         */
        $requestStatuses = $this->orderItemFinishRequestService->getForAdminStatusesWithCounts(
            $orderItemFinishRequestsForLabels
        );

        /**
         * Getting order item finish request order item statuses with counts
         */
        $toOrderItemStatuses = $this->orderItemFinishRequestService->getForAdminToOrderItemStatusesWithCounts(
            $orderItemFinishRequestsForLabels
        );

        /**
         * Checking pagination existence
         */
        if ($request->input('paginated') === true) {

            /**
             * Paginating order item finish requests
             */
            $paginatedOrderItemFinishRequests = paginateCollection(
                $orderItemFinishRequests,
                $request->input('per_page') ?
                    $request->input('per_page') :
                    $this->orderItemFinishRequestRepository->getPerPage()
            );

            return $this->setPagination($paginatedOrderItemFinishRequests)->respondWithSuccess(
                $this->transformItem([],
                    new OrderItemFinishRequestListPageTransformer(
                        new Collection($paginatedOrderItemFinishRequests->items()),
                        $requestStatuses,
                        $toOrderItemStatuses
                    )
                )['order_item_finish_request_list'],
                trans('validations/api/admin/order/orderItem/finishRequest/index.result.success')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem([],
                new OrderItemFinishRequestListPageTransformer(
                    $orderItemFinishRequests,
                    $requestStatuses,
                    $toOrderItemStatuses
                )
            )['order_item_finish_request_list'],
            trans('validations/api/admin/order/orderItem/finishRequest/index.result.success')
        );
    }
}
