<?php

namespace App\Http\Controllers\Api\General\Dashboard\Finance\Buyer;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\General\Dashboard\Finance\Buyer\Interfaces\TipInvoiceForBuyerControllerInterface;
use App\Http\Requests\Api\General\Dashboard\Finance\Buyer\Invoice\Tip\IndexRequest;
use App\Repositories\Tip\TipInvoiceRepository;
use App\Services\Auth\AuthService;
use App\Services\File\PdfService;
use App\Transformers\Api\General\Dashboard\Finance\Buyer\Invoice\Tip\Pdf\TipInvoiceForBuyerPdfTransformer;
use App\Transformers\Api\General\Dashboard\Finance\Buyer\Invoice\Tip\TipInvoiceForBuyerPageTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Mpdf\MpdfException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TipInvoiceForBuyerController
 *
 * @package App\Http\Controllers\Api\General\Dashboard\Finance\Buyer
 */
final class TipInvoiceForBuyerController extends BaseController implements TipInvoiceForBuyerControllerInterface
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
     * TipInvoiceForBuyerController constructor
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
        $tipInvoices = $this->tipInvoiceRepository->getForDashboardBuyerFilteredPaginated(
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
            $this->transformItem([], new TipInvoiceForBuyerPageTransformer(
                new Collection($tipInvoices->items())
            ))['tip_invoice_for_buyer_page'],
            trans('validations/api/general/dashboard/finance/buyer/invoice/tip/index.result.success')
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
                trans('validations/api/general/dashboard/finance/buyer/invoice/tip/viewPdf.result.error.find')
            );
        }

        /**
         * Checking a tip buyer invoice owner
         */
        if (!AuthService::user()->is(
            $tipBuyerInvoice->tip
                ->buyer
        )) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/finance/buyer/invoice/tip/viewPdf.result.error.owner')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($tipBuyerInvoice, new TipInvoiceForBuyerPdfTransformer),
            trans('validations/api/general/dashboard/finance/buyer/invoice/tip/viewPdf.result.success')
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
                trans('validations/api/general/dashboard/finance/buyer/invoice/tip/downloadPdf.result.error.find'),
                null,
                422
            );
        }

        /**
         * Checking a tip buyer invoice owner
         */
        if (!AuthService::user()->is(
            $tipBuyerInvoice->tip
                ->buyer
        )) {
            throw new BaseException(
                trans('validations/api/general/dashboard/finance/buyer/invoice/tip/downloadPdf.result.error.owner'),
                null,
                400
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
