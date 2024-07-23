<?php

namespace App\Http\Controllers\Api\General\Dashboard\Finance\Seller;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\General\Dashboard\Finance\Seller\Interfaces\WithdrawalReceiptControllerInterface;
use App\Http\Requests\Api\General\Dashboard\Finance\Seller\Withdrawal\Receipt\IndexRequest;
use App\Repositories\Receipt\WithdrawalReceiptRepository;
use App\Services\Auth\AuthService;
use App\Services\File\PdfService;
use App\Transformers\Api\General\Dashboard\Finance\Seller\Withdrawal\Pdf\WithdrawalReceiptPdfTransformer;
use App\Transformers\Api\General\Dashboard\Finance\Seller\Withdrawal\WithdrawalReceiptPageTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Mpdf\MpdfException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class WithdrawalReceiptController
 *
 * @package App\Http\Controllers\Api\General\Dashboard\Finance\Seller
 */
final class WithdrawalReceiptController extends BaseController implements WithdrawalReceiptControllerInterface
{
    /**
     * @var PdfService
     */
    protected PdfService $pdfService;

    /**
     * @var WithdrawalReceiptRepository
     */
    protected WithdrawalReceiptRepository $withdrawalReceiptRepository;

    /**
     * WithdrawalReceiptController constructor
     */
    public function __construct()
    {
        /** @var PdfService pdfService */
        $this->pdfService = new PdfService();

        /** @var WithdrawalReceiptRepository withdrawalReceiptRepository */
        $this->withdrawalReceiptRepository = new WithdrawalReceiptRepository();
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
         * Getting withdrawal receipts
         */
        $withdrawalReceipts = $this->withdrawalReceiptRepository->getForDashboardFilteredPaginated(
            AuthService::user(),
            $request->input('request_id'),
            $request->input('receipt_id'),
            $request->input('request_date_from'),
            $request->input('request_date_to'),
            $request->input('receipt_date_from'),
            $request->input('receipt_date_to'),
            $request->input('amount'),
            $request->input('payment_methods_ids'),
            $request->input('request_statuses_ids'),
            $request->input('receipt_statuses_ids'),
            $request->input('per_page'),
            $request->input('page')
        );

        return $this->setPagination($withdrawalReceipts)->respondWithSuccess(
            $this->transformItem([], new WithdrawalReceiptPageTransformer(
                new Collection($withdrawalReceipts->items())
            ))['withdrawal_receipt_page'],
            trans('validations/api/general/dashboard/finance/seller/withdrawal/receipt/index.result.success')
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
         * Getting withdrawal receipt
         */
        $withdrawalReceipt = $this->withdrawalReceiptRepository->findFullById($id);

        /**
         * Checking withdrawal receipt existence
         */
        if (!$withdrawalReceipt) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/finance/seller/withdrawal/receipt/viewPdf.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($withdrawalReceipt, new WithdrawalReceiptPdfTransformer),
            trans('validations/api/general/dashboard/finance/seller/withdrawal/receipt/viewPdf.result.success')
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
         * Getting withdrawal receipt
         */
        $withdrawalReceipt = $this->withdrawalReceiptRepository->findFullById($id);

        /**
         * Checking withdrawal receipt existence
         */
        if (!$withdrawalReceipt) {
            throw new BaseException(
                trans('validations/api/general/dashboard/finance/seller/withdrawal/receipt/downloadPdf.result.error.find'),
                null,
                422
            );
        }

        /**
         * Getting withdrawal receipt pdf
         */
        $withdrawalReceiptPdf = $this->pdfService->getWithdrawalReceipt(
            $withdrawalReceipt
        );

        return $this->respondWithMpdfDownload(
            $withdrawalReceiptPdf,
            $withdrawalReceipt->full_id . '.pdf'
        );
    }
}
