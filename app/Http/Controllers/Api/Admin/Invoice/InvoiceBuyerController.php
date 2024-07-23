<?php

namespace App\Http\Controllers\Api\Admin\Invoice;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Exports\Api\Admin\Invoice\InvoiceBuyerExport;
use App\Http\Controllers\Api\Admin\Invoice\Interfaces\InvoiceBuyerControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\Invoice\Buyer\ExportRequest;
use App\Http\Requests\Api\Admin\Invoice\Buyer\IndexRequest;
use App\Lists\Invoice\Type\InvoiceTypeList;
use App\Repositories\Order\OrderInvoiceRepository;
use App\Services\File\PdfService;
use App\Services\Invoice\InvoiceService;
use App\Services\Order\OrderItemService;
use App\Services\User\UserService;
use App\Services\Vybe\VybeService;
use App\Transformers\Api\Admin\Invoice\Buyer\InvoiceBuyerListPageTransformer;
use App\Transformers\Api\Admin\Invoice\Buyer\InvoiceBuyerPageTransformer;
use App\Transformers\Api\Admin\Invoice\Buyer\Pdf\InvoiceBuyerPdfTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\MpdfException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use PhpOffice\PhpSpreadsheet\Exception as PhpSpreadsheetException;
use PhpOffice\PhpSpreadsheet\Writer\Exception as PhpSpreadsheetWriterException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class InvoiceBuyerController
 *
 * @package App\Http\Controllers\Api\Admin\Invoice
 */
class InvoiceBuyerController extends BaseController implements InvoiceBuyerControllerInterface
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
     * @var PdfService
     */
    protected PdfService $pdfService;

    /**
     * @var VybeService
     */
    protected VybeService $vybeService;

    /**
     * @var UserService
     */
    protected UserService $userService;

    /**
     * InvoiceBuyerController constructor
     */
    public function __construct()
    {
        /** @var InvoiceService invoiceService */
        $this->invoiceService = new InvoiceService();

        /** @var OrderInvoiceRepository orderInvoiceRepository */
        $this->orderInvoiceRepository = new OrderInvoiceRepository();

        /** @var OrderItemService orderItemService */
        $this->orderItemService = new OrderItemService();

        /** @var PdfService pdfService */
        $this->pdfService = new PdfService();

        /** @var VybeService vybeService */
        $this->vybeService = new VybeService();

        /** @var UserService userService */
        $this->userService = new UserService();
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
         * Getting buyer invoices with pagination
         */
        $buyerInvoices = $this->orderInvoiceRepository->getFilteredForBuyer(
            $request->input('invoice_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('order_overview_id'),
            $request->input('buyer'),
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
        $buyerInvoicesForLabels = $this->orderInvoiceRepository->getFilteredForBuyerForAdminLabels(
            $request->input('invoice_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('order_overview_id'),
            $request->input('buyer'),
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
                    new InvoiceBuyerListPageTransformer(
                        new Collection($paginatedBuyerInvoices->items()),
                        $vybeTypes,
                        $orderItemPaymentStatuses,
                        $invoiceStatuses
                    )
                )['buyer_invoice_list'],
                trans('validations/api/admin/invoice/buyer/index.result.success')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem([],
                new InvoiceBuyerListPageTransformer(
                    $buyerInvoices,
                    $vybeTypes,
                    $orderItemPaymentStatuses,
                    $invoiceStatuses
                )
            )['buyer_invoice_list'],
            trans('validations/api/admin/invoice/buyer/index.result.success')
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
         * Getting buyer invoice
         */
        $buyerInvoice = $this->orderInvoiceRepository->findFullById(
            InvoiceTypeList::getBuyer(),
            $id
        );

        /**
         * Checking buyer invoice existence
         */
        if (!$buyerInvoice) {
            return $this->respondWithError(
                trans('validations/api/admin/invoice/buyer/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($buyerInvoice, new InvoiceBuyerPageTransformer),
            trans('validations/api/admin/invoice/buyer/show.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function viewPdf(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting buyer invoice
         */
        $buyerInvoice = $this->orderInvoiceRepository->findFullById(
            InvoiceTypeList::getBuyer(),
            $id
        );

        /**
         * Checking buyer invoice existence
         */
        if (!$buyerInvoice) {
            return $this->respondWithError(
                trans('validations/api/admin/invoice/buyer/viewPdf.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($buyerInvoice, new InvoiceBuyerPdfTransformer),
            trans('validations/api/admin/invoice/buyer/viewPdf.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return Response
     *
     * @throws BaseException
     * @throws DatabaseException
     * @throws MpdfException
     */
    public function downloadPdf(
        int $id
    ) : Response
    {
        /**
         * Getting buyer invoice
         */
        $buyerInvoice = $this->orderInvoiceRepository->findFullById(
            InvoiceTypeList::getBuyer(),
            $id
        );

        /**
         * Checking buyer invoice existence
         */
        if (!$buyerInvoice) {
            throw new BaseException(
                trans('validations/api/admin/invoice/buyer/downloadPdf.result.error.find'),
                null,
                422
            );
        }

        /**
         * Getting buyer invoice pdf
         */
        $buyerInvoicePdf = $this->pdfService->getInvoiceForBuyer(
            $buyerInvoice
        );

        return $this->respondWithMpdfDownload(
            $buyerInvoicePdf,
            $buyerInvoice->full_id . '.pdf'
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
         * Getting buyer invoices
         */
        $buyerInvoices = $this->orderInvoiceRepository->getFilteredForBuyer(
            $request->input('invoice_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('order_overview_id'),
            $request->input('buyer'),
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
                new InvoiceBuyerExport($buyerInvoices),
                'buyer_invoices.pdf',
                \Maatwebsite\Excel\Excel::MPDF, [
                    'Content-Type' => 'application/pdf'
                ]
            );
        }

        return Excel::download(
            new InvoiceBuyerExport($buyerInvoices),
            'buyer_invoices.xls',
            \Maatwebsite\Excel\Excel::XLS, [
                'Content-Type' => 'text/csv'
            ]
        );
    }
}
