<?php

namespace App\Http\Controllers\Api\General\Dashboard\Finance\Seller;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\General\Dashboard\Finance\Seller\Interfaces\TipInvoiceForSellerControllerInterface;
use App\Http\Requests\Api\General\Dashboard\Finance\Seller\Invoice\Tip\IndexRequest;
use App\Repositories\Tip\TipInvoiceRepository;
use App\Services\Auth\AuthService;
use App\Services\File\PdfService;
use App\Transformers\Api\General\Dashboard\Finance\Seller\Invoice\Tip\Pdf\TipInvoiceForSellerPdfTransformer;
use App\Transformers\Api\General\Dashboard\Finance\Seller\Invoice\Tip\TipInvoiceForSellerPageTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Mpdf\MpdfException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TipInvoiceForSellerController
 *
 * @package App\Http\Controllers\Api\General\Dashboard\Finance\Seller
 */
final class TipInvoiceForSellerController extends BaseController implements TipInvoiceForSellerControllerInterface
{
    /**
     * @var PdfService
     */
    protected PdfService $pdfService;

    /**
     * @var TipInvoiceRepository
     */
    protected TipInvoiceRepository $tipInvoiceRepository;

    /**
     * TipInvoiceForSellerController constructor
     */
    public function __construct()
    {
        /** @var PdfService pdfService */
        $this->pdfService = new PdfService();

        /** @var TipInvoiceRepository tipInvoiceRepository */
        $this->tipInvoiceRepository = new TipInvoiceRepository();
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
         * Getting tip invoices
         */
        $tipInvoices = $this->tipInvoiceRepository->getForDashboardSellerFilteredPaginated(
            AuthService::user(),
            $request->input('item_id'),
            $request->input('invoice_id'),
            $request->input('username'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('amount'),
            $request->input('vybe_types_ids'),
            $request->input('order_item_statuses_ids'),
            $request->input('invoice_statuses_ids'),
            $request->input('per_page'),
            $request->input('page')
        );

        return $this->setPagination($tipInvoices)->respondWithSuccess(
            $this->transformItem([], new TipInvoiceForSellerPageTransformer(
                new Collection($tipInvoices->items())
            ))['tip_invoice_for_seller_page'],
            trans('validations/api/general/dashboard/finance/seller/invoice/tip/index.result.success')
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
                trans('validations/api/general/dashboard/finance/seller/invoice/tip/viewPdf.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($tipSellerInvoice, new TipInvoiceForSellerPdfTransformer),
            trans('validations/api/general/dashboard/finance/seller/invoice/tip/viewPdf.result.success')
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
                trans('validations/api/general/dashboard/finance/seller/invoice/tip/downloadPdf.result.error.find'),
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
