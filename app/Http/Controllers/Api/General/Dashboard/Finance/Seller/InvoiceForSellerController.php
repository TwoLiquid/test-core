<?php

namespace App\Http\Controllers\Api\General\Dashboard\Finance\Seller;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\General\Dashboard\Finance\Seller\Interfaces\InvoiceForSellerControllerInterface;
use App\Http\Requests\Api\General\Dashboard\Finance\Seller\Invoice\IndexRequest;
use App\Lists\Invoice\Type\InvoiceTypeList;
use App\Repositories\Order\OrderInvoiceRepository;
use App\Services\Auth\AuthService;
use App\Services\File\PdfService;
use App\Services\Invoice\InvoiceService;
use App\Transformers\Api\General\Dashboard\Finance\Seller\Invoice\InvoiceForSellerPageTransformer;
use App\Transformers\Api\General\Dashboard\Finance\Seller\Invoice\Pdf\OrderInvoicePdfTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Mpdf\MpdfException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class InvoiceForSellerController
 *
 * @package App\Http\Controllers\Api\General\Dashboard\Finance\Seller
 */
final class InvoiceForSellerController extends BaseController implements InvoiceForSellerControllerInterface
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
     * @var PdfService
     */
    protected PdfService $pdfService;

    /**
     * InvoiceForSellerController constructor
     */
    public function __construct()
    {
        /** @var InvoiceService invoiceService */
        $this->invoiceService = new InvoiceService();

        /** @var OrderInvoiceRepository orderInvoiceRepository */
        $this->orderInvoiceRepository = new OrderInvoiceRepository();

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
         * Getting order invoices
         */
        $orderInvoices = $this->orderInvoiceRepository->getForDashboardSellerFilteredPaginated(
            AuthService::user(),
            $request->input('invoice_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('username'),
            $request->input('earned'),
            $request->input('vybe_types_ids'),
            $request->input('order_item_statuses_ids'),
            $request->input('invoice_statuses_ids'),
            $request->input('page'),
            $request->input('per_page')
        );

        return $this->setPagination($orderInvoices)->respondWithSuccess(
            $this->transformItem([], new InvoiceForSellerPageTransformer(
                new Collection($orderInvoices->items())
            ))['invoice_for_seller_page'],
            trans('validations/api/general/dashboard/finance/seller/invoice/index.result.success')
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
         * Getting order invoice
         */
        $orderInvoice = $this->orderInvoiceRepository->findFullById(
            InvoiceTypeList::getSeller(),
            $id
        );

        /**
         * Checking order invoice existence
         */
        if (!$orderInvoice) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/finance/seller/invoice/viewPdf.result.error.find')
            );
        }

        /**
         * Checking order invoice owner
         */
        if (!$this->invoiceService->belongsToSeller(
            AuthService::user(),
            $orderInvoice
        )) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/finance/seller/invoice/viewPdf.result.error.owner')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($orderInvoice, new OrderInvoicePdfTransformer),
            trans('validations/api/general/dashboard/finance/seller/invoice/viewPdf.result.success')
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
         * Getting order invoice
         */
        $orderInvoice = $this->orderInvoiceRepository->findFullById(
            InvoiceTypeList::getSeller(),
            $id
        );

        /**
         * Checking order invoice existence
         */
        if (!$orderInvoice) {
            throw new BaseException(
                trans('validations/api/general/dashboard/finance/seller/invoice/downloadPdf.result.error.find'),
                null,
                422
            );
        }

        /**
         * Checking order invoice owner
         */
        if (!$this->invoiceService->belongsToSeller(
            AuthService::user(),
            $orderInvoice
        )) {
            throw new BaseException(
                trans('validations/api/general/dashboard/finance/seller/invoice/downloadPdf.result.error.owner'),
                null,
                400
            );
        }

        /**
         * Getting order invoice pdf
         */
        $orderInvoicePdf = $this->pdfService->getInvoiceForSeller(
            $orderInvoice
        );

        return $this->respondWithMpdfDownload(
            $orderInvoicePdf,
            $orderInvoice->full_id . '.pdf'
        );
    }
}
