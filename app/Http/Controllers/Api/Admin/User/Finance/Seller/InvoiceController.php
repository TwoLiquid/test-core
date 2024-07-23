<?php

namespace App\Http\Controllers\Api\Admin\User\Finance\Seller;

use App\Exceptions\DatabaseException;
use App\Exports\Api\Admin\User\Finance\Seller\InvoiceExport;
use App\Http\Controllers\Api\Admin\User\Finance\Seller\Interfaces\InvoiceControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\User\Finance\Seller\Invoice\IndexRequest;
use App\Http\Requests\Api\Admin\User\Finance\Seller\Invoice\ExportRequest;
use App\Repositories\Order\OrderInvoiceRepository;
use App\Repositories\User\UserRepository;
use App\Services\Invoice\InvoiceService;
use App\Services\Order\OrderItemService;
use App\Services\Vybe\VybeService;
use App\Transformers\Api\Admin\User\Finance\Seller\Invoice\InvoiceListPageTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use PhpOffice\PhpSpreadsheet\Exception as PhpSpreadsheetException;
use PhpOffice\PhpSpreadsheet\Writer\Exception as PhpSpreadsheetWriterException;

/**
 * Class InvoiceController
 *
 * @package App\Http\Controllers\Api\Admin\User\Finance\Seller
 */
class InvoiceController extends BaseController implements InvoiceControllerInterface
{
    /**
     * @var InvoiceService
     */
    protected InvoiceService $invoiceService;

    /**
     * @var OrderInvoiceRepository
     */
    protected OrderInvoiceRepository $orderInvoiceRepository;

    /**
     * @var OrderItemService
     */
    protected OrderItemService $orderItemService;

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * @var VybeService
     */
    protected VybeService $vybeService;

    /**
     * InvoiceController constructor
     */
    public function __construct()
    {
        /** @var InvoiceService invoiceService */
        $this->invoiceService = new InvoiceService();

        /** @var OrderInvoiceRepository orderInvoiceRepository */
        $this->orderInvoiceRepository = new OrderInvoiceRepository();

        /** @var OrderItemService orderItemService */
        $this->orderItemService = new OrderItemService();

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
                trans('validations/api/admin/user/finance/seller/invoice/index.result.error.find')
            );
        }

        /**
         * Getting seller invoices with pagination
         */
        $sellerInvoices = $this->orderInvoiceRepository->getFilteredByUserForSeller(
            $user,
            $request->input('invoice_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('buyer'),
            $request->input('vybe_types_ids'),
            $request->input('order_item_statuses_ids'),
            $request->input('invoice_statuses_ids'),
            $request->input('sort_by'),
            $request->input('sort_order')
        );

        /**
         * Getting seller invoices for labels
         */
        $sellerInvoicesForLabels = $this->orderInvoiceRepository->getFilteredByUserForSellerForAdminLabels(
            $user,
            $request->input('invoice_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('buyer')
        );

        /**
         * Getting vybe types with counts
         */
        $vybeTypes = $this->vybeService->getForAdminTypesByOrderInvoicesIds(
            $sellerInvoicesForLabels
        );

        /**
         * Getting order item statuses with counts
         */
        $orderItemStatuses = $this->orderItemService->getForAdminStatusesByOrderInvoicesIds(
            $sellerInvoicesForLabels
        );

        /**
         * Getting buyer invoice statuses with counts
         */
        $invoiceStatuses = $this->invoiceService->getForAdminBuyerStatusesByIds(
            $sellerInvoicesForLabels
        );

        /**
         * Checking pagination existence
         */
        if ($request->input('paginated') === true) {

            /**
             * Paginating seller invoices
             */
            $paginatedBuyerInvoices = paginateCollection(
                $sellerInvoices,
                $request->input('per_page') ?
                    $request->input('per_page') :
                    $this->orderInvoiceRepository->getPerPage()
            );

            return $this->setPagination($paginatedBuyerInvoices)->respondWithSuccess(
                $this->transformItem([],
                    new InvoiceListPageTransformer(
                        new Collection($paginatedBuyerInvoices->items()),
                        $vybeTypes,
                        $orderItemStatuses,
                        $invoiceStatuses
                    )
                )['seller_invoice_list'],
                trans('validations/api/admin/user/finance/seller/invoice/index.result.success')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem([],
                new InvoiceListPageTransformer(
                    $sellerInvoices,
                    $vybeTypes,
                    $orderItemStatuses,
                    $invoiceStatuses
                )
            )['seller_invoice_list'],
            trans('validations/api/admin/user/finance/seller/invoice/index.result.success')
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
         * Getting seller invoices
         */
        $buyerInvoices = $this->orderInvoiceRepository->getFilteredByUserForSeller(
            $user,
            $request->input('invoice_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('buyer'),
            $request->input('vybe_types_ids'),
            $request->input('order_item_statuses_ids'),
            $request->input('invoice_statuses_ids'),
            $request->input('sort_by'),
            $request->input('sort_order')
        );

        /**
         * Checking an exporting file type
         */
        if ($type == 'pdf') {
            Excel::store(
                new InvoiceExport($buyerInvoices),
                'seller_invoices.pdf',
                'public',
                \Maatwebsite\Excel\Excel::MPDF
            );

            return Excel::download(
                new InvoiceExport($buyerInvoices),
                'seller_invoices.pdf',
                \Maatwebsite\Excel\Excel::MPDF, [
                    'Content-Type' => 'application/pdf'
                ]
            );
        }

        Excel::store(
            new InvoiceExport($buyerInvoices),
            'seller_invoices.xls',
            'public',
            \Maatwebsite\Excel\Excel::XLS
        );

        return Excel::download(
            new InvoiceExport($buyerInvoices),
            'seller_invoices.xls',
            \Maatwebsite\Excel\Excel::XLS, [
                'Content-Type' => 'text/csv'
            ]
        );
    }
}
