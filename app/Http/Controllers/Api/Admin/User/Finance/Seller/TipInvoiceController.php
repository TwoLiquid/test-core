<?php

namespace App\Http\Controllers\Api\Admin\User\Finance\Seller;

use App\Exceptions\DatabaseException;
use App\Exports\Api\Admin\User\Finance\Seller\TipInvoiceExport;
use App\Http\Controllers\Api\Admin\User\Finance\Seller\Interfaces\TipInvoiceControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\User\Finance\Seller\Tip\ExportRequest;
use App\Http\Requests\Api\Admin\User\Finance\Seller\Tip\IndexRequest;
use App\Repositories\Tip\TipInvoiceRepository;
use App\Repositories\User\UserRepository;
use App\Services\Order\OrderItemService;
use App\Services\Tip\TipInvoiceService;
use App\Transformers\Api\Admin\User\Finance\Seller\Tip\TipInvoiceListPageTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Exception as PhpSpreadsheetException;
use PhpOffice\PhpSpreadsheet\Writer\Exception as PhpSpreadsheetWriterException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Class TipInvoiceController
 *
 * @package App\Http\Controllers\Api\Admin\User\Finance\Seller
 */
class TipInvoiceController extends BaseController implements TipInvoiceControllerInterface
{
    /**
     * @var OrderItemService
     */
    protected OrderItemService $orderItemService;

    /**
     * @var TipInvoiceRepository
     */
    protected TipInvoiceRepository $tipInvoiceRepository;

    /**
     * @var TipInvoiceService
     */
    protected TipInvoiceService $tipInvoiceService;

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * TipInvoiceController constructor
     */
    public function __construct()
    {
        /** @var OrderItemService orderItemService */
        $this->orderItemService = new OrderItemService();

        /** @var TipInvoiceRepository tipInvoiceRepository */
        $this->tipInvoiceRepository = new TipInvoiceRepository();

        /** @var TipInvoiceService tipInvoiceService */
        $this->tipInvoiceService = new TipInvoiceService();

        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();
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
                trans('validations/api/admin/user/finance/seller/tip/index.result.error.find')
            );
        }

        /**
         * Getting tip seller invoices with pagination
         */
        $tipSellerInvoices = $this->tipInvoiceRepository->getAllFilteredForSeller(
            $user,
            $request->input('item_id'),
            $request->input('vybe_type_id'),
            $request->input('buyer'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('order_item_statuses_ids'),
            $request->input('invoice_id'),
            $request->input('invoice_statuses_ids'),
            $request->input('sort_by'),
            $request->input('sort_order')
        );

        /**
         * Getting tip seller invoices for labels
         */
        $tipSellerInvoicesForLabels = $this->tipInvoiceRepository->getAllFilteredForSellerForAdminLabels(
            $user,
            $request->input('item_id'),
            $request->input('vybe_type_id'),
            $request->input('buyer'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('invoice_id')
        );

        /**
         * Getting order item payment statuses with counts
         */
        $orderItemPaymentStatuses = $this->orderItemService->getForAdminStatusesByTipInvoicesIds(
            $tipSellerInvoicesForLabels
        );

        /**
         * Getting tip seller invoice statuses with counts
         */
        $tipInvoiceStatuses = $this->tipInvoiceService->getForAdminSellerStatusesByIds(
            $tipSellerInvoicesForLabels
        );

        /**
         * Checking pagination existence
         */
        if ($request->input('paginated') === true) {

            /**
             * Paginating tip seller invoices
             */
            $paginatedTipSellerInvoices = paginateCollection(
                $tipSellerInvoices,
                $request->input('per_page') ?
                    $request->input('per_page') :
                    $this->tipInvoiceRepository->getPerPage()
            );

            return $this->setPagination($paginatedTipSellerInvoices)->respondWithSuccess(
                $this->transformItem([],
                    new TipInvoiceListPageTransformer(
                        new Collection($paginatedTipSellerInvoices->items()),
                        $orderItemPaymentStatuses,
                        $tipInvoiceStatuses
                    )
                )['tip_seller_invoice_list'],
                trans('validations/api/admin/user/finance/seller/tip/index.result.success')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem([],
                new TipInvoiceListPageTransformer(
                    $tipSellerInvoices,
                    $orderItemPaymentStatuses,
                    $tipInvoiceStatuses
                )
            )['tip_seller_invoice_list'],
            trans('validations/api/admin/user/finance/seller/tip/index.result.success')
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
         * Getting tip seller invoices
         */
        $tipSellerInvoices = $this->tipInvoiceRepository->getAllFilteredForSeller(
            $user,
            $request->input('item_id'),
            $request->input('vybe_type_id'),
            $request->input('buyer'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('order_item_statuses_ids'),
            $request->input('invoice_id'),
            $request->input('invoice_statuses_ids'),
            $request->input('sort_by'),
            $request->input('sort_order')
        );

        /**
         * Checking an exporting file type
         */
        if ($type == 'pdf') {
            return Excel::download(
                new TipInvoiceExport($tipSellerInvoices),
                'seller_tip_invoices.pdf',
                \Maatwebsite\Excel\Excel::MPDF, [
                    'Content-Type' => 'application/pdf'
                ]
            );
        }

        Excel::store(
            new TipInvoiceExport($tipSellerInvoices),
            'order_overviews.xls',
            'public',
            \Maatwebsite\Excel\Excel::XLS
        );

        return Excel::download(
            new TipInvoiceExport($tipSellerInvoices),
            'seller_tip_invoices.xls',
            \Maatwebsite\Excel\Excel::XLS, [
                'Content-Type' => 'text/csv'
            ]
        );
    }
}
