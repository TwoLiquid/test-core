<?php

namespace App\Http\Controllers\Api\Admin\User\Finance\Buyer;

use App\Exceptions\DatabaseException;
use App\Exports\Api\Admin\User\Finance\Buyer\InvoiceExport;
use App\Http\Controllers\Api\Admin\User\Finance\Buyer\Interfaces\InvoiceControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\User\Finance\Buyer\Invoice\ExportRequest;
use App\Http\Requests\Api\Admin\User\Finance\Buyer\Invoice\IndexRequest;
use App\Repositories\Order\OrderInvoiceRepository;
use App\Repositories\User\UserRepository;
use App\Services\Invoice\InvoiceService;
use App\Services\Order\OrderItemService;
use App\Services\Vybe\VybeService;
use App\Transformers\Api\Admin\User\Finance\Buyer\Invoice\InvoiceListPageTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use PhpOffice\PhpSpreadsheet\Exception as PhpSpreadsheetException;
use PhpOffice\PhpSpreadsheet\Writer\Exception as PhpSpreadsheetWriterException;

/**
 * Class InvoiceBuyerController
 *
 * @package App\Http\Controllers\Api\Admin\Invoice
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
                trans('validations/api/admin/user/finance/buyer/invoice/index.result.error.find')
            );
        }

        /**
         * Getting buyer invoices with pagination
         */
        $buyerInvoices = $this->orderInvoiceRepository->getFilteredByUserForBuyer(
            $user,
            $request->input('invoice_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('order_overview_id'),
            $request->input('seller'),
            $request->input('vybe_types_ids'),
            $request->input('order_item_payment_statuses_ids'),
            $request->input('invoice_statuses_ids'),
            $request->input('sort_by'),
            $request->input('sort_order')
        );

        /**
         * Getting buyer invoices for labels
         */
        $buyerInvoicesForLabels = $this->orderInvoiceRepository->getFilteredByUserForBuyerForAdminLabels(
            $user,
            $request->input('invoice_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('order_overview_id'),
            $request->input('seller')
        );

        /**
         * Getting vybe types with counts
         */
        $vybeTypes = $this->vybeService->getForAdminTypesByOrderInvoicesIds(
            $buyerInvoicesForLabels
        );

        /**
         * Getting order item payment statuses with counts
         */
        $orderItemPaymentStatuses = $this->orderItemService->getForAdminPaymentStatusesByOrderInvoicesIds(
            $buyerInvoicesForLabels
        );

        /**
         * Getting buyer invoice statuses with counts
         */
        $invoiceStatuses = $this->invoiceService->getForAdminBuyerStatusesByIds(
            $buyerInvoicesForLabels
        );

        /**
         * Checking pagination existence
         */
        if ($request->input('paginated') === true) {

            /**
             * Paginating buyer invoices
             */
            $paginatedBuyerInvoices = paginateCollection(
                $buyerInvoices,
                $request->input('per_page') ?
                    $request->input('per_page') :
                    $this->orderInvoiceRepository->getPerPage()
            );

            return $this->setPagination($paginatedBuyerInvoices)->respondWithSuccess(
                $this->transformItem([],
                    new InvoiceListPageTransformer(
                        new Collection($paginatedBuyerInvoices->items()),
                        $vybeTypes,
                        $orderItemPaymentStatuses,
                        $invoiceStatuses
                    )
                )['buyer_invoice_list'],
                trans('validations/api/admin/user/finance/buyer/invoice/index.result.success')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem([],
                new InvoiceListPageTransformer(
                    $buyerInvoices,
                    $vybeTypes,
                    $orderItemPaymentStatuses,
                    $invoiceStatuses
                )
            )['buyer_invoice_list'],
            trans('validations/api/admin/user/finance/buyer/invoice/index.result.success')
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
         * Getting buyer invoices
         */
        $buyerInvoices = $this->orderInvoiceRepository->getFilteredByUserForBuyer(
            $user,
            $request->input('invoice_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('order_overview_id'),
            $request->input('seller'),
            $request->input('vybe_types_ids'),
            $request->input('order_item_payment_statuses_ids'),
            $request->input('invoice_statuses_ids'),
            $request->input('sort_by'),
            $request->input('sort_order')
        );

        /**
         * Checking an exporting file type
         */
        if ($type == 'pdf') {
            return Excel::download(
                new InvoiceExport($buyerInvoices),
                'buyer_invoices.pdf',
                \Maatwebsite\Excel\Excel::MPDF, [
                    'Content-Type' => 'application/pdf'
                ]
            );
        }

        return Excel::download(
            new InvoiceExport($buyerInvoices),
            'buyer_invoices.xls',
            \Maatwebsite\Excel\Excel::XLS, [
                'Content-Type' => 'text/csv'
            ]
        );
    }
}
