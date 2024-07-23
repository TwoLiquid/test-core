<?php

namespace App\Http\Controllers\Api\Admin\Order;

use App\Exceptions\DatabaseException;
use App\Exports\Api\Admin\Order\OrderOverviewExport;
use App\Http\Controllers\Api\Admin\Order\Interfaces\OrderOverviewControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\Order\OrderOverview\ExportRequest;
use App\Http\Requests\Api\Admin\Order\OrderOverview\IndexRequest;
use App\Repositories\Order\OrderRepository;
use App\Services\Order\OrderItemService;
use App\Services\Vybe\VybeService;
use App\Transformers\Api\Admin\Order\OrderOverviewListPageTransformer;
use App\Transformers\Api\Admin\Order\OrderOverviewTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use PhpOffice\PhpSpreadsheet\Exception as PhpSpreadsheetException;
use PhpOffice\PhpSpreadsheet\Writer\Exception as PhpSpreadsheetWriterException;

/**
 * Class OrderOverviewController
 *
 * @package App\Http\Controllers\Api\Admin\Order
 */
class OrderOverviewController extends BaseController implements OrderOverviewControllerInterface
{
    /**
     * @var OrderItemService
     */
    protected OrderItemService $orderItemService;

    /**
     * @var OrderRepository
     */
    protected OrderRepository $orderRepository;

    /**
     * @var VybeService
     */
    protected VybeService $vybeService;

    /**
     * OrderOverviewController constructor
     */
    public function __construct()
    {
        /** @var OrderItemService orderItemService */
        $this->orderItemService = new OrderItemService();

        /** @var OrderRepository orderRepository */
        $this->orderRepository = new OrderRepository();

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
         * Getting order overviews with pagination
         */
        $orders = $this->orderRepository->getAllFiltered(
            $request->input('overview_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('buyer'),
            $request->input('seller'),
            $request->input('order_item_id'),
            $request->input('vybe_types_ids'),
            $request->input('order_item_payment_statuses_ids'),
            $request->input('sort_by'),
            $request->input('sort_order')
        );

        /**
         * Getting order overviews for labels
         */
        $ordersForLabels = $this->orderRepository->getAllFilteredForAdminLabels(
            $request->input('overview_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('buyer'),
            $request->input('seller'),
            $request->input('order_item_id')
        );

        /**
         * Getting vybe types with counts
         */
        $vybeTypes = $this->vybeService->getForAdminTypesByOrdersIds(
            $ordersForLabels
        );

        /**
         * Getting order item payment statuses with count
         */
        $orderItemPaymentStatuses = $this->orderItemService->getForAdminPaymentStatusesByOrdersIds(
            $ordersForLabels
        );

        /**
         * Checking pagination existence
         */
        if ($request->input('paginated') === true) {

            /**
             * Paginating orders
             */
            $paginatedOrders = paginateCollection(
                $orders,
                $request->input('per_page') ?
                    $request->input('per_page') :
                    $this->orderRepository->getPerPage()
            );

            return $this->setPagination($paginatedOrders)->respondWithSuccess(
                $this->transformItem([],
                    new OrderOverviewListPageTransformer(
                        new Collection($paginatedOrders->items()),
                        $vybeTypes,
                        $orderItemPaymentStatuses
                    )
                )['order_overview_list'],
                trans('validations/api/admin/order/orderOverview/index.result.success')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem([],
                new OrderOverviewListPageTransformer(
                    $orders,
                    $vybeTypes,
                    $orderItemPaymentStatuses
                )
            )['order_overview_list'],
            trans('validations/api/admin/order/orderOverview/index.result.success')
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
         * Getting order overview
         */
        $order = $this->orderRepository->findFullById($id);

        return $this->respondWithSuccess(
            $this->transformItem($order, new OrderOverviewTransformer),
            trans('validations/api/admin/order/orderOverview/show.result.success')
        );
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
         * Getting order overviews
         */
        $orders = $this->orderRepository->getAllFiltered(
            $request->input('overview_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('buyer'),
            $request->input('seller'),
            $request->input('order_item_id'),
            $request->input('vybe_types_ids'),
            $request->input('order_item_payment_statuses_ids'),
            $request->input('sort_by'),
            $request->input('sort_order')
        );

        /**
         * Checking an exporting file type
         */
        if ($type == 'pdf') {
            return Excel::download(
                new OrderOverviewExport($orders),
                'order_overviews.pdf',
                \Maatwebsite\Excel\Excel::MPDF, [
                    'Content-Type' => 'application/pdf'
                ]
            );
        }

        return Excel::download(
            new OrderOverviewExport($orders),
            'order_overviews.xls',
            \Maatwebsite\Excel\Excel::XLS, [
                'Content-Type' => 'text/csv'
            ]
        );
    }
}
