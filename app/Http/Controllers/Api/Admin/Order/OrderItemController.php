<?php

namespace App\Http\Controllers\Api\Admin\Order;

use App\Exceptions\DatabaseException;
use App\Exports\Api\Admin\Order\OrderItemExport;
use App\Http\Controllers\Api\Admin\Order\Interfaces\OrderItemControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\Order\OrderItem\IndexRequest;
use App\Http\Requests\Api\Admin\Order\OrderItem\ExportRequest;
use App\Lists\Order\Item\Status\OrderItemStatusList;
use App\Repositories\Order\OrderItemRepository;
use App\Services\Order\OrderItemService;
use App\Services\Vybe\VybeService;
use App\Transformers\Api\Admin\Order\OrderItemListPageTransformer;
use App\Transformers\Api\Admin\Order\OrderItemPageTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use PhpOffice\PhpSpreadsheet\Exception as PhpSpreadsheetException;
use PhpOffice\PhpSpreadsheet\Writer\Exception as PhpSpreadsheetWriterException;

/**
 * Class OrderItemController
 *
 * @package App\Http\Controllers\Api\Admin\Order
 */
class OrderItemController extends BaseController implements OrderItemControllerInterface
{
    /**
     * @var OrderItemRepository
     */
    protected OrderItemRepository $orderItemRepository;

    /**
     * @var OrderItemService
     */
    protected OrderItemService $orderItemService;

    /**
     * @var VybeService
     */
    protected VybeService $vybeService;

    /**
     * OrderItemController constructor
     */
    public function __construct()
    {
        /** @var OrderItemRepository orderItemRepository */
        $this->orderItemRepository = new OrderItemRepository();

        /** @var OrderItemService orderItemService */
        $this->orderItemService = new OrderItemService();

        /** @var VybeService vybeService */
        $this->vybeService = new VybeService();
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
         * Getting order items with pagination
         */
        $orderItems = $this->orderItemRepository->getAllFiltered(
            $request->input('order_item_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('buyer'),
            $request->input('seller'),
            $request->input('vybe_title'),
            $request->input('vybe_types_ids'),
            $request->input('order_item_statuses_ids'),
            $request->input('order_item_payment_statuses_ids'),
            $request->input('sort_by'),
            $request->input('sort_order')
        );

        /**
         * Getting order items for labels
         */
        $orderItemsForLabels = $this->orderItemRepository->getAllFilteredForAdminLabels(
            $request->input('order_item_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('buyer'),
            $request->input('seller'),
            $request->input('vybe_title')
        );

        /**
         * Getting order item statuses with counts
         */
        $orderItemStatuses = $this->orderItemService->getForAdminStatusesByIds(
            $orderItemsForLabels
        );

        /**
         * Getting order item payment statuses with counts
         */
        $orderItemPaymentStatuses = $this->orderItemService->getForAdminPaymentStatusesByIds(
            $orderItemsForLabels
        );

        /**
         * Getting vybe types with counts
         */
        $vybeTypes = $this->vybeService->getForAdminTypesByOrderItemsIds(
            $orderItemsForLabels
        );

        /**
         * Checking pagination existence
         */
        if ($request->input('paginated') === true) {

            /**
             * Paginating order items
             */
            $paginatedOrderItems = paginateCollection(
                $orderItems,
                $request->input('per_page') ?
                    $request->input('per_page') :
                    $this->orderItemRepository->getPerPage()
            );

            return $this->setPagination($paginatedOrderItems)->respondWithSuccess(
                $this->transformItem([],
                    new OrderItemListPageTransformer(
                        new Collection($paginatedOrderItems->items()),
                        $vybeTypes,
                        $orderItemStatuses,
                        $orderItemPaymentStatuses
                    )
                )['order_item_list'],
                trans('validations/api/admin/order/orderItem/index.result.success')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem([],
                new OrderItemListPageTransformer(
                    $orderItems,
                    $vybeTypes,
                    $orderItemStatuses,
                    $orderItemPaymentStatuses
                )
            )['order_item_list'],
            trans('validations/api/admin/order/orderItem/index.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function show(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting order item
         */
        $orderItem = $this->orderItemRepository->findFullById($id);

        return $this->respondWithSuccess([
            'order_item'          => $this->transformItem(
                $orderItem, new OrderItemPageTransformer
            )['order_item'],
            'order_item_statuses' => OrderItemStatusList::getItems()
        ], trans('validations/api/admin/order/orderItem/show.result.success'));
    }

    /**
     * @param string $type
     * @param ExportRequest $request
     *
     * @return BinaryFileResponse
     *
     * @throws DatabaseException
     * @throws PhpSpreadsheetException
     * @throws PhpSpreadsheetWriterException
     */
    public function export(
        string $type,
        ExportRequest $request
    ) : BinaryFileResponse
    {
        /**
         * Getting order items
         */
        $orderItems = $this->orderItemRepository->getAllFiltered(
            $request->input('order_item_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('buyer'),
            $request->input('seller'),
            $request->input('vybe_title'),
            $request->input('vybe_types_ids'),
            $request->input('order_item_statuses_ids'),
            $request->input('order_item_payment_statuses_ids'),
            $request->input('sort_by'),
            $request->input('sort_order')
        );

        /**
         * Checking an exporting file type
         */
        if ($type == 'pdf') {
            return Excel::download(
                new OrderItemExport($orderItems),
                'order_items.pdf',
                \Maatwebsite\Excel\Excel::MPDF, [
                    'Content-Type' => 'application/pdf'
                ]
            );
        }

        return Excel::download(
            new OrderItemExport($orderItems),
            'order_items.xls',
            \Maatwebsite\Excel\Excel::XLS, [
                'Content-Type' => 'text/csv'
            ]
        );
    }
}
