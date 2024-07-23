<?php

namespace App\Http\Controllers\Api\Admin\User\Finance\Seller;

use App\Exceptions\DatabaseException;
use App\Exports\Api\Admin\User\Finance\Seller\SaleOverviewExport;
use App\Http\Controllers\Api\Admin\User\Finance\Seller\Interfaces\SaleOverviewControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\User\Finance\Seller\SaleOverview\ExportRequest;
use App\Http\Requests\Api\Admin\User\Finance\Seller\SaleOverview\IndexRequest;
use App\Repositories\Sale\SaleRepository;
use App\Repositories\User\UserRepository;
use App\Services\Order\OrderItemService;
use App\Services\Vybe\VybeService;
use App\Transformers\Api\Admin\User\Finance\Seller\SaleOverview\SaleOverviewListPageTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use PhpOffice\PhpSpreadsheet\Exception as PhpSpreadsheetException;
use PhpOffice\PhpSpreadsheet\Writer\Exception as PhpSpreadsheetWriterException;

/**
 * Class SaleOverviewController
 *
 * @package App\Http\Controllers\Api\Admin\User\Finance\Seller
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
     * @var UserRepository
     */
    protected UserRepository $userRepository;

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

        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();

        /** @var VybeService vybeService */
        $this->vybeService = new VybeService();
    }

    /**
     * @param int $id
     * @param IndexRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function index(
        int $id,
        IndexRequest $request
    ) : JsonResponse
    {
        /**
         * Getting user
         */
        $user = $this->userRepository->findByIdForAdmin($id);

        if (!$user) {
            return $this->respondWithError(
                trans('validations/api/admin/user/finance/seller/saleOverview/index.result.error.find')
            );
        }

        /**
         * Getting sale overviews with pagination
         */
        $sales = $this->saleRepository->getAllFilteredForUser(
            $user,
            $request->input('overview_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('buyer'),
            $request->input('order_item_id'),
            $request->input('vybe_types_ids'),
            $request->input('order_item_payment_statuses_ids'),
            $request->input('sort_by'),
            $request->input('sort_order')
        );

        /**
         * Getting sale overviews for labels
         */
        $salesForLabels = $this->saleRepository->getAllFilteredForUserForAdminLabels(
            $user,
            $request->input('overview_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('buyer'),
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
                trans('validations/api/admin/user/finance/seller/saleOverview/index.result.success')
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
            trans('validations/api/admin/user/finance/seller/saleOverview/index.result.success')
        );
    }

    /**
     * @param int $id
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
        int $id,
        string $type,
        ExportRequest $request
    ) : BinaryFileResponse
    {
        /**
         * Getting user
         */
        $user = $this->userRepository->findByIdForAdmin($id);

        /**
         * Getting sale overviews
         */
        $sales = $this->saleRepository->getAllFilteredForUser(
            $user,
            $request->input('overview_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('buyer'),
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
