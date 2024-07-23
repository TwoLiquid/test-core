<?php

namespace App\Http\Controllers\Api\Admin\Invoice;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Exports\Api\Admin\Invoice\TipInvoiceSellerExport;
use App\Http\Controllers\Api\Admin\Invoice\Interfaces\TipInvoiceSellerControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\Invoice\Tip\Seller\ExportRequest;
use App\Http\Requests\Api\Admin\Invoice\Tip\Seller\IndexRequest;
use App\Lists\Invoice\Type\InvoiceTypeList;
use App\Repositories\Tip\TipInvoiceRepository;
use App\Services\File\PdfService;
use App\Services\Order\OrderItemService;
use App\Services\Tip\TipInvoiceService;
use App\Transformers\Api\Admin\Invoice\Tip\Seller\TipInvoiceSellerListPageTransformer;
use App\Transformers\Api\Admin\Invoice\Tip\Seller\TipInvoiceSellerPageTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\MpdfException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use PhpOffice\PhpSpreadsheet\Exception as PhpSpreadsheetException;
use PhpOffice\PhpSpreadsheet\Writer\Exception as PhpSpreadsheetWriterException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TipInvoiceSellerController
 *
 * @package App\Http\Controllers\Api\Admin\Invoice
 */
class TipInvoiceSellerController extends BaseController implements TipInvoiceSellerControllerInterface
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
     * TipInvoiceSellerController constructor
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
         * Getting tip seller invoices with pagination
         */
        $tipSellerInvoices = $this->tipInvoiceRepository->getAllFiltered(
            InvoiceTypeList::getTipSeller(),
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
         * Getting tip seller invoices for labels
         */
        $tipSellerInvoicesForLabels = $this->tipInvoiceRepository->getAllFilteredForAdminLabels(
            InvoiceTypeList::getTipSeller(),
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
                    new TipInvoiceSellerListPageTransformer(
                        new Collection($paginatedTipSellerInvoices->items()),
                        $orderItemStatuses,
                        $tipInvoiceStatuses
                    )
                )['tip_seller_invoice_list'],
                trans('validations/api/admin/invoice/tip/seller/index.result.success')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem([],
                new TipInvoiceSellerListPageTransformer(
                    $tipSellerInvoices,
                    $orderItemStatuses,
                    $tipInvoiceStatuses
                )
            )['tip_seller_invoice_list'],
            trans('validations/api/admin/invoice/tip/seller/index.result.success')
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
         * Getting tip seller invoice
         */
        $tipSellerInvoice = $this->tipInvoiceRepository->findFullById($id);

        return $this->respondWithSuccess(
            $this->transformItem($tipSellerInvoice, new TipInvoiceSellerPageTransformer),
            trans('validations/api/admin/invoice/tip/seller/show.result.success')
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
         * Getting tip seller invoices
         */
        $tipSellerInvoices = $this->tipInvoiceRepository->getAllFiltered(
            InvoiceTypeList::getTipSeller(),
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
                new TipInvoiceSellerExport($tipSellerInvoices),
                'seller_tip_invoices.pdf',
                \Maatwebsite\Excel\Excel::MPDF, [
                    'Content-Type' => 'application/pdf'
                ]
            );
        }

        Excel::store(
            new TipInvoiceSellerExport($tipSellerInvoices),
            'order_overviews.xls',
            'public',
            \Maatwebsite\Excel\Excel::XLS
        );

        return Excel::download(
            new TipInvoiceSellerExport($tipSellerInvoices),
            'seller_tip_invoices.xls',
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
         * Getting tip seller invoice
         */
        $tipSellerInvoice = $this->tipInvoiceRepository->findFullById($id);

        /**
         * Checking tip seller invoice existence
         */
        if (!$tipSellerInvoice) {
            return $this->respondWithError(
                trans('validations/api/admin/invoice/tip/seller/viewPdf.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($tipSellerInvoice, new TipInvoiceSellerPageTransformer),
            trans('validations/api/admin/invoice/tip/seller/viewPdf.result.success')
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
         * Getting tip seller invoice
         */
        $tipSellerInvoice = $this->tipInvoiceRepository->findFullById($id);

        /**
         * Checking tip buyer invoice existence
         */
        if (!$tipSellerInvoice) {
            throw new BaseException(
                trans('validations/api/admin/invoice/tip/seller/downloadPdf.result.error.find'),
                null,
                422
            );
        }

        /**
         * Getting tip seller invoice pdf
         */
        $tipSellerInvoicePdf = $this->pdfService->getTipInvoiceForSeller(
            $tipSellerInvoice
        );

        return $this->respondWithMpdfDownload(
            $tipSellerInvoicePdf,
            $tipSellerInvoice->full_id . '.pdf'
        );
    }
}
