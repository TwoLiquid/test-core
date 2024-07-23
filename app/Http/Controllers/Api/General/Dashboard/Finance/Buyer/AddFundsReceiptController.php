<?php

namespace App\Http\Controllers\Api\General\Dashboard\Finance\Buyer;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\General\Dashboard\Finance\Buyer\Interfaces\AddFundsReceiptControllerInterface;
use App\Http\Requests\Api\General\Dashboard\Finance\Buyer\AddFunds\Receipt\IndexRequest;
use App\Repositories\Receipt\AddFundsReceiptRepository;
use App\Services\Auth\AuthService;
use App\Services\File\PdfService;
use App\Transformers\Api\General\Dashboard\Finance\Buyer\AddFunds\AddFundsReceiptPageTransformer;
use App\Transformers\Api\General\Dashboard\Finance\Buyer\AddFunds\Pdf\AddFundsReceiptPdfTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Mpdf\MpdfException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AddFundsReceiptController
 *
 * @package App\Http\Controllers\Api\General\Dashboard\Finance\Buyer
 */
final class AddFundsReceiptController extends BaseController implements AddFundsReceiptControllerInterface
{
    /**
     * @var AddFundsReceiptRepository
     */
    protected AddFundsReceiptRepository $addFundsReceiptRepository;

    /**
     * @var PdfService
     */
    protected PdfService $pdfService;

    /**
     * AddFundsReceiptController constructor
     */
    public function __construct()
    {
        /** @var AddFundsReceiptRepository addFundsReceiptRepository */
        $this->addFundsReceiptRepository = new AddFundsReceiptRepository();

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
         * Getting add funds receipts
         */
        $addFundsReceipts = $this->addFundsReceiptRepository->getForDashboardFilteredPaginated(
            AuthService::user(),
            $request->input('receipt_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('amount'),
            $request->input('payment_fee'),
            $request->input('total'),
            $request->input('payment_methods_ids'),
            $request->input('add_funds_receipt_statuses_ids'),
            $request->input('per_page'),
            $request->input('page')
        );

        return $this->setPagination($addFundsReceipts)->respondWithSuccess(
            $this->transformItem([], new AddFundsReceiptPageTransformer(
                new Collection($addFundsReceipts->items())
            ))['add_funds_receipt_page'],
            trans('validations/api/general/dashboard/finance/buyer/addFunds/receipt/index.result.success')
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
         * Getting add funds receipt
         */
        $addFundsReceipt = $this->addFundsReceiptRepository->findFullById($id);

        /**
         * Checking withdrawal receipt existence
         */
        if (!$addFundsReceipt) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/finance/buyer/addFunds/receipt/viewPdf.result.error.find')
            );
        }

        /**
         * Checking an add funds receipt owner
         */
        if (!AuthService::user()->is(
            $addFundsReceipt->user
        )) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/finance/buyer/addFunds/receipt/viewPdf.result.error.owner')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($addFundsReceipt, new AddFundsReceiptPdfTransformer),
            trans('validations/api/general/dashboard/finance/buyer/addFunds/receipt/viewPdf.result.success')
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
         * Getting add funds receipt
         */
        $addFundsReceipt = $this->addFundsReceiptRepository->findFullById($id);

        /**
         * Checking add funds receipt existence
         */
        if (!$addFundsReceipt) {
            throw new BaseException(
                trans('validations/api/general/dashboard/finance/buyer/addFunds/receipt/downloadPdf.result.error.find'),
                null,
                422
            );
        }

        /**
         * Checking an add funds receipt owner
         */
        if (!AuthService::user()->is(
            $addFundsReceipt->user
        )) {
            throw new BaseException(
                trans('validations/api/general/dashboard/finance/buyer/addFunds/receipt/downloadPdf.result.error.owner'),
                null,
                400
            );
        }

        /**
         * Getting add funds receipt pdf
         */
        $addFundsReceiptPdf = $this->pdfService->getAddFundsReceipt(
            $addFundsReceipt
        );

        return $this->respondWithMpdfDownload(
            $addFundsReceiptPdf,
            $addFundsReceipt->full_id . '.pdf'
        );
    }
}
