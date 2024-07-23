<?php

namespace App\Http\Controllers\Api\Admin\Order;

use App\Exceptions\DatabaseException;
use App\Exports\Api\Admin\Order\SaleOverviewExport;
use App\Http\Controllers\Api\Admin\Order\Interfaces\SaleOverviewControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\Order\SaleOverview\ExportRequest;
use App\Http\Requests\Api\Admin\Order\SaleOverview\IndexRequest;
use App\Repositories\Sale\SaleRepository;
use App\Services\Order\OrderItemService;
use App\Services\Vybe\VybeService;
use App\Transformers\Api\Admin\Order\SaleOverviewListPageTransformer;
use App\Transformers\Api\Admin\Order\SaleOverviewTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use PhpOffice\PhpSpreadsheet\Exception as PhpSpreadsheetException;
use PhpOffice\PhpSpreadsheet\Writer\Exception as PhpSpreadsheetWriterException;

/**
 * Class SaleOverviewController
 *
 * @package App\Http\Controllers\Api\Admin\Order
 */
class SaleOverviewController extends BaseController implements SaleOverviewControllerInterface
{
    /**
     * @var OrderItemService
     */
    protected OrderItemService $orderItemService;

    /**
     * @var SaleRepository
     */
    protected SaleRepository $saleRepository;

    /**
     * @var VybeService
     */
    protected VybeService $vybeService;

    /**
     * SaleOverviewController constructor
     */
    public function __construct()
    {
        /** @var OrderItemService orderItemService */
        $this->orderItemService = new OrderItemService();

        /** @var SaleRepository saleRepository */
        $this->saleRepository = new SaleRepository();

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
         * Getting sale overviews with pagination
         */
        $sales = $this->saleRepository->getAllFiltered(
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
         * Getting sale overviews for labels
         */
        $salesForLabels = $this->saleRepository->getAllFilteredForAdminLabels(
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
        $vybeTypes = $this->vybeService->getForAdminTypesBySalesIds(
            $salesForLabels
        );

        /**
         * Getting order item payment statuses with count
         */
        $orderItemPaymentStatuses = $this->orderItemService->getForAdminPaymentStatusesBySalesIds(
            $salesForLabels
        );

        /**
         * Checking pagination existence
         */
        if ($request->input('paginated') === true) {

            /**
             * Paginating orders
             */
            $paginatedSales = paginateCollection(
                $sales,
                $request->input('per_page') ?
                    $request->input('per_page') :
                    $this->saleRepository->getPerPage()
            );

            return $this->setPagination($paginatedSales)->respondWithSuccess(
                $this->transformItem([],
                    new SaleOverviewListPageTransformer(
                        new Collection($paginatedSales->items()),
                        $vybeTypes,
                        $orderItemPaymentStatuses
                    )
                )['sale_overview_list'],
                trans('validations/api/admin/order/saleOverview/index.result.success')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem([],
                new SaleOverviewListPageTransformer(
                    $sales,
                    $vybeTypes,
                    $orderItemPaymentStatuses
                )
            )['sale_overview_list'],
            trans('validations/api/admin/order/saleOverview/index.result.success')
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
         * Getting sale overview
         */
        $sale = $this->saleRepository->findFullById($id);

        return $this->respondWithSuccess(
            $this->transformItem($sale, new SaleOverviewTransformer),
            trans('validations/api/admin/order/saleOverview/show.result.success')
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
         * Getting sale overviews
         */
        $sales = $this->saleRepository->getAllFiltered(
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
                new SaleOverviewExport($sales),
                'sale_overviews.pdf',
                \Maatwebsite\Excel\Excel::MPDF, [
                    'Content-Type' => 'application/pdf'
                ]
            );
        }

        return Excel::download(
            new SaleOverviewExport($sales),
            'sale_overviews.xls',
            \Maatwebsite\Excel\Excel::XLS, [
                'Content-Type' => 'text/csv'
            ]
        );
    }
}
