<?php

namespace App\Http\Controllers\Api\Admin\Invoice;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Exports\Api\Admin\Invoice\InvoiceSellerExport;
use App\Http\Controllers\Api\Admin\Invoice\Interfaces\InvoiceSellerControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\Invoice\Seller\ExportRequest;
use App\Http\Requests\Api\Admin\Invoice\Seller\IndexRequest;
use App\Lists\Invoice\Type\InvoiceTypeList;
use App\Repositories\Order\OrderInvoiceRepository;
use App\Services\File\PdfService;
use App\Services\Invoice\InvoiceService;
use App\Services\Order\OrderItemService;
use App\Services\Vybe\VybeService;
use App\Transformers\Api\Admin\Invoice\Seller\InvoiceSellerListPageTransformer;
use App\Transformers\Api\Admin\Invoice\Seller\InvoiceSellerPageTransformer;
use App\Transformers\Api\Admin\Invoice\Seller\Pdf\InvoiceSellerPdfTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\MpdfException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use PhpOffice\PhpSpreadsheet\Exception as PhpSpreadsheetException;
use PhpOffice\PhpSpreadsheet\Writer\Exception as PhpSpreadsheetWriterException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class InvoiceSellerController
 *
 * @package App\Http\Controllers\Api\Admin\Invoice
 */
class InvoiceSellerController extends BaseController implements InvoiceSellerControllerInterface
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
     * InvoiceSellerController constructor
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
         * Getting seller invoices with pagination
         */
        $sellerInvoices = $this->orderInvoiceRepository->getFilteredForSeller(
            $request->input('invoice_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('vybe_version'),
            $request->input('buyer'),
            $request->input('seller'),
            $request->input('vybe_types_ids'),
            $request->input('order_item_statuses_ids'),
            $request->input('invoice_statuses_ids'),
            $request->input('sort_by'),
            $request->input('sort_order')
        );

        /**
         * Getting seller invoices for labels
         */
        $sellerInvoicesForLabels = $this->orderInvoiceRepository->getFilteredForSellerForAdminLabels(
            $request->input('invoice_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('vybe_version'),
            $request->input('buyer'),
            $request->input('seller')
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
        $invoiceStatuses = $this->invoiceService->getForAdminSellerStatusesByIds(
            $sellerInvoicesForLabels
        );

        /**
         * Checking pagination existence
         */
        if ($request->input('paginated') === true) {

            /**
             * Paginating seller invoices
             */
            $paginatedSellerInvoices = paginateCollection(
                $sellerInvoices,
                $request->input('per_page') ?
                    $request->input('per_page') :
                    $this->orderInvoiceRepository->getPerPage()
            );

            return $this->setPagination($paginatedSellerInvoices)->respondWithSuccess(
                $this->transformItem([],
                    new InvoiceSellerListPageTransformer(
                        new Collection($paginatedSellerInvoices->items()),
                        $vybeTypes,
                        $orderItemStatuses,
                        $invoiceStatuses
                    )
                )['seller_invoice_list'],
                trans('validations/api/admin/invoice/seller/index.result.success')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem([],
                new InvoiceSellerListPageTransformer(
                    $sellerInvoices,
                    $vybeTypes,
                    $orderItemStatuses,
                    $invoiceStatuses
                )
            )['seller_invoice_list'],
            trans('validations/api/admin/invoice/seller/index.result.success')
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
         * Getting seller invoice
         */
        $buyerInvoice = $this->orderInvoiceRepository->findFullById(
            InvoiceTypeList::getSeller(),
            $id
        );

        return $this->respondWithSuccess(
            $this->transformItem($buyerInvoice, new InvoiceSellerPageTransformer),
            trans('validations/api/admin/invoice/seller/show.result.success')
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
         * Getting seller invoice
         */
        $sellerInvoice = $this->orderInvoiceRepository->findFullById(
            InvoiceTypeList::getSeller(),
            $id
        );

        /**
         * Checking seller invoice existence
         */
        if (!$sellerInvoice) {
            return $this->respondWithError(
                trans('validations/api/admin/invoice/seller/viewPdf.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($sellerInvoice, new InvoiceSellerPdfTransformer),
            trans('validations/api/admin/invoice/seller/viewPdf.result.success')
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
         * Getting seller invoice
         */
        $sellerInvoice = $this->orderInvoiceRepository->findFullById(
            InvoiceTypeList::getSeller(),
            $id
        );

        /**
         * Checking seller invoice existence
         */
        if (!$sellerInvoice) {
            throw new BaseException(
                trans('validations/api/admin/invoice/seller/downloadPdf.result.error.find'),
                null,
                422
            );
        }

        /**
         * Getting seller invoice pdf
         */
        $sellerInvoicePdf = $this->pdfService->getInvoiceForSeller(
            $sellerInvoice
        );

        return $this->respondWithMpdfDownload(
            $sellerInvoicePdf,
            $sellerInvoice->full_id . '.pdf'
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
         * Getting seller invoices
         */
        $sellerInvoices = $this->orderInvoiceRepository->getFilteredForSeller(
            $request->input('invoice_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('vybe_version'),
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
                new InvoiceSellerExport($sellerInvoices),
                'seller_invoices.pdf',
                \Maatwebsite\Excel\Excel::MPDF, [
                    'Content-Type' => 'application/pdf'
                ]
            );
        }

        return Excel::download(
            new InvoiceSellerExport($sellerInvoices),
            'seller_invoices.xls',
            \Maatwebsite\Excel\Excel::XLS, [
                'Content-Type' => 'text/csv'
            ]
        );
    }
}
