<?php

namespace App\Http\Controllers\Api\General\Dashboard\Finance\Buyer;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\General\Dashboard\Finance\Buyer\Interfaces\InvoiceForBuyerControllerInterface;
use App\Http\Requests\Api\General\Dashboard\Finance\Buyer\Invoice\IndexRequest;
use App\Lists\Invoice\Type\InvoiceTypeList;
use App\Repositories\Order\OrderInvoiceRepository;
use App\Services\Auth\AuthService;
use App\Services\File\PdfService;
use App\Transformers\Api\General\Dashboard\Finance\Buyer\Invoice\InvoiceForBuyerPageTransformer;
use App\Transformers\Api\General\Dashboard\Finance\Buyer\Invoice\Pdf\OrderInvoicePdfTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Mpdf\MpdfException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class InvoiceForBuyerController
 *
 * @package App\Http\Controllers\Api\General\Dashboard\Finance\Buyer
 */
final class InvoiceForBuyerController extends BaseController implements InvoiceForBuyerControllerInterface
{
    /**
     * @var OrderInvoiceRepository
     */
    protected OrderInvoiceRepository $orderInvoiceRepository;

    /**
     * @var PdfService
     */
    protected PdfService $pdfService;

    /**
     * InvoiceForBuyerController constructor
     */
    public function __construct()
    {
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
        $orderInvoices = $this->orderInvoiceRepository->getForDashboardBuyerFilteredPaginated(
            AuthService::user(),
            $request->input('invoice_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('username'),
            $request->input('total'),
            $request->input('vybe_types_ids'),
            $request->input('order_item_payment_statuses_ids'),
            $request->input('invoice_statuses_ids'),
            $request->input('page'),
            $request->input('per_page')
        );

        return $this->setPagination($orderInvoices)->respondWithSuccess(
            $this->transformItem([], new InvoiceForBuyerPageTransformer(
                new Collection($orderInvoices->items())
            ))['invoice_for_buyer_page'],
            trans('validations/api/general/dashboard/finance/buyer/invoice/index.result.success')
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
            InvoiceTypeList::getBuyer(),
            $id
        );

        /**
         * Checking order invoice existence
         */
        if (!$orderInvoice) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/finance/buyer/invoice/viewPdf.result.error.find')
            );
        }

        /**
         * Checking order invoice owner
         */
        if (!AuthService::user()->is(
            $orderInvoice->order
                ->buyer
        )) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/finance/buyer/invoice/viewPdf.result.error.owner')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($orderInvoice, new OrderInvoicePdfTransformer),
            trans('validations/api/general/dashboard/finance/buyer/invoice/viewPdf.result.success')
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
            InvoiceTypeList::getBuyer(),
            $id
        );

        /**
         * Checking order invoice existence
         */
        if (!$orderInvoice) {
            throw new BaseException(
                trans('validations/api/general/dashboard/finance/buyer/invoice/downloadPdf.result.error.find'),
                null,
                422
            );
        }

        /**
         * Checking order invoice owner
         */
        if (!AuthService::user()->is(
            $orderInvoice->order
                ->buyer
        )) {
            throw new BaseException(
                trans('validations/api/general/dashboard/finance/buyer/invoice/downloadPdf.result.error.owner'),
                null,
                400
            );
        }

        /**
         * Getting order invoice pdf
         */
        $orderInvoicePdf = $this->pdfService->getInvoiceForBuyer(
            $orderInvoice
        );

        return $this->respondWithMpdfDownload(
            $orderInvoicePdf,
            $orderInvoice->full_id . '.pdf'
        );
    }
}
