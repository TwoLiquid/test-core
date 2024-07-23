<?php

namespace App\Http\Controllers\Api\Admin\Invoice;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Exports\Api\Admin\Invoice\TipInvoiceBuyerExport;
use App\Http\Controllers\Api\Admin\Invoice\Interfaces\TipInvoiceBuyerControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\Invoice\Tip\Buyer\ExportRequest;
use App\Http\Requests\Api\Admin\Invoice\Tip\Buyer\IndexRequest;
use App\Lists\Invoice\Type\InvoiceTypeList;
use App\Repositories\Tip\TipInvoiceRepository;
use App\Services\File\PdfService;
use App\Services\Order\OrderItemService;
use App\Services\Tip\TipInvoiceService;
use App\Transformers\Api\Admin\Invoice\Tip\Buyer\TipInvoiceBuyerListPageTransformer;
use App\Transformers\Api\Admin\Invoice\Tip\Buyer\TipInvoiceBuyerPageTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\MpdfException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use PhpOffice\PhpSpreadsheet\Exception as PhpSpreadsheetException;
use PhpOffice\PhpSpreadsheet\Writer\Exception as PhpSpreadsheetWriterException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TipInvoiceBuyerController
 *
 * @package App\Http\Controllers\Api\Admin\Invoice
 */
class TipInvoiceBuyerController extends BaseController implements TipInvoiceBuyerControllerInterface
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
     * @var PdfService
     */
    protected PdfService $pdfService;

    /**
     * TipInvoiceBuyerController constructor
     */
    public function __construct()
    {
        /** @var OrderItemService orderItemService */
        $this->orderItemService = new OrderItemService();

        /** @var TipInvoiceRepository tipInvoiceRepository */
        $this->tipInvoiceRepository = new TipInvoiceRepository();

        /** @var TipInvoiceService tipInvoiceService */
        $this->tipInvoiceService = new TipInvoiceService();

        /** @var PdfService pdfService */
        $this->pdfService = new PdfService();
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
         * Getting tip buyer invoices with pagination
         */
        $tipBuyerInvoices = $this->tipInvoiceRepository->getAllFiltered(
            InvoiceTypeList::getTipBuyer(),
            $request->input('item_id'),
            $request->input('vybe_type_id'),
            $request->input('buyer'),
            $request->input('seller'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('order_item_statuses_ids'),
            $request->input('invoice_id'),
            $request->input('invoice_statuses_ids'),
            $request->input('sort_by'),
            $request->input('sort_order')
        );

        /**
         * Getting tip buyer invoices for labels
         */
        $tipBuyerInvoicesForLabels = $this->tipInvoiceRepository->getAllFilteredForAdminLabels(
            InvoiceTypeList::getTipBuyer(),
            $request->input('item_id'),
            $request->input('vybe_type_id'),
            $request->input('buyer'),
            $request->input('seller'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('invoice_id')
        );

        /**
         * Getting order item statuses with counts
         */
        $orderItemStatuses = $this->orderItemService->getForAdminStatusesByTipInvoicesIds(
            $tipBuyerInvoicesForLabels
        );

        /**
         * Getting tip buyer invoice statuses with counts
         */
        $tipInvoiceStatuses = $this->tipInvoiceService->getForAdminBuyerStatusesByIds(
            $tipBuyerInvoicesForLabels
        );

        /**
         * Checking pagination existence
         */
        if ($request->input('paginated') === true) {

            /**
             * Paginating tip buyer invoices
             */
            $paginatedTipBuyerInvoices = paginateCollection(
                $tipBuyerInvoices,
                $request->input('per_page') ?
                    $request->input('per_page') :
                    $this->tipInvoiceRepository->getPerPage()
            );

            return $this->setPagination($paginatedTipBuyerInvoices)->respondWithSuccess(
                $this->transformItem([],
                    new TipInvoiceBuyerListPageTransformer(
                        new Collection($paginatedTipBuyerInvoices->items()),
                        $orderItemStatuses,
                        $tipInvoiceStatuses
                    )
                )['tip_buyer_invoice_list'],
                trans('validations/api/admin/invoice/tip/buyer/index.result.success')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem([],
                new TipInvoiceBuyerListPageTransformer(
                    $tipBuyerInvoices,
                    $orderItemStatuses,
                    $tipInvoiceStatuses
                )
            )['tip_buyer_invoice_list'],
            trans('validations/api/admin/invoice/tip/buyer/index.result.success')
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
         * Getting tip buyer invoice
         */
        $tipBuyerInvoice = $this->tipInvoiceRepository->findFullById($id);

        return $this->respondWithSuccess(
            $this->transformItem($tipBuyerInvoice, new TipInvoiceBuyerPageTransformer),
            trans('validations/api/admin/invoice/tip/buyer/show.result.success')
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
         * Getting tip buyer invoices
         */
        $tipBuyerInvoices = $this->tipInvoiceRepository->getAllFiltered(
            InvoiceTypeList::getTipBuyer(),
            $request->input('item_id'),
            $request->input('vybe_type_id'),
            $request->input('buyer'),
            $request->input('seller'),
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
                new TipInvoiceBuyerExport($tipBuyerInvoices),
                'buyer_tip_invoices.pdf',
                \Maatwebsite\Excel\Excel::MPDF, [
                    'Content-Type' => 'application/pdf'
                ]
            );
        }

        return Excel::download(
            new TipInvoiceBuyerExport($tipBuyerInvoices),
            'buyer_tip_invoices.xls',
            \Maatwebsite\Excel\Excel::XLS, [
                'Content-Type' => 'text/csv'
            ]
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
         * Getting tip buyer invoice
         */
        $tipBuyerInvoice = $this->tipInvoiceRepository->findFullById($id);

        /**
         * Checking tip buyer invoice existence
         */
        if (!$tipBuyerInvoice) {
            return $this->respondWithError(
                trans('validations/api/admin/invoice/tip/buyer/viewPdf.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($tipBuyerInvoice, new TipInvoiceBuyerPageTransformer),
            trans('validations/api/admin/invoice/tip/buyer/viewPdf.result.success')
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
         * Getting tip buyer invoice
         */
        $tipBuyerInvoice = $this->tipInvoiceRepository->findFullById($id);

        /**
         * Checking tip buyer invoice existence
         */
        if (!$tipBuyerInvoice) {
            throw new BaseException(
                trans('validations/api/admin/invoice/tip/buyer/downloadPdf.result.error.find'),
                null,
                422
            );
        }

        /**
         * Getting tip buyer invoice pdf
         */
        $tipBuyerInvoicePdf = $this->pdfService->getTipInvoiceForBuyer(
            $tipBuyerInvoice
        );

        return $this->respondWithMpdfDownload(
            $tipBuyerInvoicePdf,
            $tipBuyerInvoice->full_id . '.pdf'
        );
    }
}
