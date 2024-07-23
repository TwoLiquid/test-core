<?php

namespace App\Http\Controllers\Api\Admin\Invoice;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Exceptions\MicroserviceException;
use App\Exports\Api\Admin\Invoice\WithdrawalReceiptExport;
use App\Http\Controllers\Api\Admin\Invoice\Interfaces\WithdrawalReceiptControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\Invoice\Withdrawal\Receipt\ExportRequest;
use App\Http\Requests\Api\Admin\Invoice\Withdrawal\Receipt\AddTransferRequest;
use App\Http\Requests\Api\Admin\Invoice\Withdrawal\Receipt\IndexRequest;
use App\Http\Requests\Api\Admin\Invoice\Withdrawal\Receipt\UploadProofFilesRequest;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\User\Balance\Type\UserBalanceTypeList;
use App\Repositories\Media\WithdrawalReceiptProofDocumentRepository;
use App\Repositories\Media\WithdrawalReceiptProofImageRepository;
use App\Repositories\Payment\PaymentMethodRepository;
use App\Repositories\Receipt\WithdrawalReceiptRepository;
use App\Repositories\Receipt\WithdrawalTransactionRepository;
use App\Services\File\PdfService;
use App\Services\Log\LogService;
use App\Services\Withdrawal\WithdrawalReceiptService;
use App\Transformers\Api\Admin\Invoice\Withdrawal\Receipt\RequestStatusTransformer;
use App\Transformers\Api\Admin\Invoice\Withdrawal\Receipt\WithdrawalReceiptListPageTransformer;
use App\Transformers\Api\Admin\Invoice\Withdrawal\Receipt\WithdrawalReceiptTransformer;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\MpdfException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use PhpOffice\PhpSpreadsheet\Exception as PhpSpreadsheetException;
use PhpOffice\PhpSpreadsheet\Writer\Exception as PhpSpreadsheetWriterException;
use Exception;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class WithdrawalReceiptController
 *
 * @package App\Http\Controllers\Api\Admin\Invoice
 */
class WithdrawalReceiptController extends BaseController implements WithdrawalReceiptControllerInterface
{
    /**
     * @var LogService
     */
    protected LogService $logService;

    /**
     * @var PaymentMethodRepository
     */
    protected PaymentMethodRepository $paymentMethodRepository;

    /**
     * @var PdfService
     */
    protected PdfService $pdfService;

    /**
     * @var WithdrawalReceiptRepository
     */
    protected WithdrawalReceiptRepository $withdrawalReceiptRepository;

    /**
     * @var WithdrawalReceiptService
     */
    protected WithdrawalReceiptService $withdrawalReceiptService;

    /**
     * @var WithdrawalTransactionRepository
     */
    protected WithdrawalTransactionRepository $withdrawalTransactionRepository;

    /**
     * @var WithdrawalReceiptProofImageRepository
     */
    protected WithdrawalReceiptProofImageRepository $withdrawalReceiptProofImageRepository;

    /**
     * @var WithdrawalReceiptProofDocumentRepository
     */
    protected WithdrawalReceiptProofDocumentRepository $withdrawalReceiptProofDocumentRepository;

    /**
     * WithdrawalReceiptController constructor
     */
    public function __construct()
    {
        /** @var LogService logService */
        $this->logService = new LogService();

        /** @var PaymentMethodRepository paymentMethodRepository */
        $this->paymentMethodRepository = new PaymentMethodRepository();

        /** @var PdfService pdfService */
        $this->pdfService = new PdfService();

        /** @var WithdrawalReceiptRepository withdrawalReceiptRepository */
        $this->withdrawalReceiptRepository = new WithdrawalReceiptRepository();

        /** @var WithdrawalReceiptService withdrawalReceiptService */
        $this->withdrawalReceiptService = new WithdrawalReceiptService();

        /** @var WithdrawalTransactionRepository withdrawalTransactionRepository */
        $this->withdrawalTransactionRepository = new WithdrawalTransactionRepository();

        /** @var WithdrawalReceiptProofImageRepository withdrawalReceiptProofImageRepository */
        $this->withdrawalReceiptProofImageRepository = new WithdrawalReceiptProofImageRepository();

        /** @var WithdrawalReceiptProofDocumentRepository withdrawalReceiptProofDocumentRepository */
        $this->withdrawalReceiptProofDocumentRepository = new WithdrawalReceiptProofDocumentRepository();
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
         * Getting withdrawal receipts with pagination
         */
        $withdrawalReceipts = $this->withdrawalReceiptRepository->getAllFiltered(
            $request->input('receipt_id'),
            $request->input('request_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('client'),
            $request->input('payout_method_id'),
            $request->input('receipt_statuses_ids'),
            $request->input('sort_by'),
            $request->input('sort_order')
        );

        /**
         * Getting withdrawal receipts for labels
         */
        $withdrawalReceiptsForLabels = $this->withdrawalReceiptRepository->getAllFilteredForAdminLabels(
            $request->input('receipt_id'),
            $request->input('request_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('client'),
            $request->input('payout_method_id')
        );

        /**
         * Getting withdrawal receipt statuses with counts
         */
        $withdrawalReceiptStatuses = $this->withdrawalReceiptService->getForAdminStatusesByIds(
            $withdrawalReceiptsForLabels
        );

        /**
         * Getting payout method
         */
        $payoutMethods = $this->paymentMethodRepository->getAllWithdrawalIntegrated();

        /**
         * Checking pagination existence
         */
        if ($request->input('paginated') === true) {

            /**
             * Paginating withdrawal receipts
             */
            $paginatedWithdrawalReceipts = paginateCollection(
                $withdrawalReceipts,
                $request->input('per_page') ?
                    $request->input('per_page') :
                    $this->withdrawalReceiptRepository->getPerPage()
            );

            return $this->setPagination($paginatedWithdrawalReceipts)->respondWithSuccess(
                $this->transformItem([],
                    new WithdrawalReceiptListPageTransformer(
                        new Collection($paginatedWithdrawalReceipts->items()),
                        $withdrawalReceiptStatuses,
                        $payoutMethods
                    )
                )['withdrawal_receipt_list'],
                trans('validations/api/admin/invoice/withdrawal/receipt/index.result.success')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem([],
                new WithdrawalReceiptListPageTransformer(
                    $withdrawalReceipts,
                    $withdrawalReceiptStatuses,
                    $payoutMethods
                )
            )['withdrawal_receipt_list'],
            trans('validations/api/admin/invoice/withdrawal/receipt/index.result.success')
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
         * Getting withdrawal receipt
         */
        $withdrawalReceipt = $this->withdrawalReceiptRepository->findFullById($id);

        /**
         * Checking withdrawal receipt existence
         */
        if (!$withdrawalReceipt) {
            $this->respondWithError(
                trans('validations/api/admin/invoice/withdrawal/receipt/show.result.error.find')
            );
        }

        /**
         * Getting request statuses
         */
        $requestStatusListItems = RequestStatusList::getItems();

        return $this->respondWithSuccess(
            array_merge(
                $this->transformItem($withdrawalReceipt, new WithdrawalReceiptTransformer(
                    $this->withdrawalReceiptProofImageRepository->getByReceipts(
                        new Collection([$withdrawalReceipt])
                    ),
                    $this->withdrawalReceiptProofDocumentRepository->getByReceipts(
                        new Collection([$withdrawalReceipt])
                    )
                )),
                $this->transformCollection($requestStatusListItems, new RequestStatusTransformer)
            ), trans('validations/api/admin/invoice/withdrawal/receipt/show.result.success')
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
                trans('validations/api/admin/invoice/withdrawal/receipt/viewPdf.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($withdrawalReceipt, new WithdrawalReceiptTransformer),
            trans('validations/api/admin/invoice/withdrawal/receipt/viewPdf.result.success')
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
                trans('validations/api/admin/invoice/withdrawal/receipt/downloadPdf.result.error.find'),
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

    /**
     * @param int $id
     * @param AddTransferRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     */
    public function addTransfer(
        int $id,
        AddTransferRequest $request
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
                trans('validations/api/admin/invoice/withdrawal/receipt/addTransfer.result.error.find')
            );
        }

        /**
         * Checking withdrawal status
         */
        if (!$withdrawalReceipt->getStatus()->isUnpaid()) {
            return $this->respondWithError(
                trans('validations/api/admin/invoice/withdrawal/receipt/addTransfer.result.error.status')
            );
        }

        /**
         * Checking amount is able to be paid
         */
        if (!$this->withdrawalReceiptService->isAvailableToAddTransfer(
            $withdrawalReceipt,
            $request->input('amount')
        )) {
            return $this->respondWithError(
                trans('validations/api/admin/invoice/withdrawal/receipt/addTransfer.result.error.amount')
            );
        }

        /**
         * Getting payout method
         */
        $payoutMethod = $this->paymentMethodRepository->findById(
            $request->input('method_id')
        );

        /**
         * Creating withdrawal transaction
         */
        $withdrawalTransaction = $this->withdrawalTransactionRepository->store(
            $withdrawalReceipt,
            $payoutMethod,
            $request->input('external_id'),
            $request->input('amount'),
            $request->input('transaction_fee'),
            null,
            $request->input('payment_date')
        );

        /**
         * Updating withdrawal receipt amounts
         */
        $this->withdrawalReceiptService->addAmountByTransfer(
            $withdrawalReceipt,
            $withdrawalTransaction
        );

        /**
         * Updating withdrawal receipt status
         */
        $withdrawalReceipt = $this->withdrawalReceiptService->updateTotalPaid(
            $withdrawalReceipt,
            $request->input('amount')
        );

        /**
         * Checking withdrawal request status
         */
        if ($withdrawalReceipt->getStatus()->isPaid()) {

            try {

                /**
                 * Creating withdrawal receipt log
                 */
                $this->logService->addWithdrawalReceiptLog(
                    $withdrawalReceipt,
                    $withdrawalReceipt->user->getSellerBalance(),
                    UserBalanceTypeList::getSeller(),
                    'paid'
                );
            } catch (Exception $exception) {

                /**
                 * Adding background error to controller stack
                 */
                $this->addBackgroundError(
                    $exception
                );
            }
        }

        return $this->respondWithSuccess(
            $this->transformItem(
                $this->withdrawalReceiptRepository->findFullById(
                    $withdrawalReceipt->id
                ), new WithdrawalReceiptTransformer(
                    $this->withdrawalReceiptProofImageRepository->getByReceipts(
                        new Collection([$withdrawalReceipt])
                    ),
                    $this->withdrawalReceiptProofDocumentRepository->getByReceipts(
                        new Collection([$withdrawalReceipt])
                    )
                )
            ),
            trans('validations/api/admin/invoice/withdrawal/receipt/addTransfer.result.success')
        );
    }

    /**
     * @param int $id
     * @param UploadProofFilesRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function uploadProofFiles(
        int $id,
        UploadProofFilesRequest $request
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
                trans('validations/api/admin/invoice/withdrawal/receipt/uploadProofFiles.result.error.find')
            );
        }

        if ($request->input('withdrawal_receipt_proof_files')) {

            /**
             * Creating withdrawal receipt proof files
             */
            $this->withdrawalReceiptService->uploadProofFiles(
                $withdrawalReceipt,
                $request->input('withdrawal_receipt_proof_files')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem(
                $this->withdrawalReceiptRepository->findFullById(
                    $withdrawalReceipt->id
                ), new WithdrawalReceiptTransformer(
                    $this->withdrawalReceiptProofImageRepository->getByReceipts(
                        new Collection([$withdrawalReceipt])
                    ),
                    $this->withdrawalReceiptProofDocumentRepository->getByReceipts(
                        new Collection([$withdrawalReceipt])
                    )
                )
            ),
            trans('validations/api/admin/invoice/withdrawal/receipt/uploadProofFiles.result.success')
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
         * Getting withdrawal receipts
         */
        $withdrawalReceipts = $this->withdrawalReceiptRepository->getAllFiltered(
            $request->input('receipt_id'),
            $request->input('request_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('client'),
            $request->input('payout_method_id'),
            $request->input('receipt_statuses_ids'),
            $request->input('sort_by'),
            $request->input('sort_order')
        );

        /**
         * Checking an exporting file type
         */
        if ($type == 'pdf') {
            return Excel::download(
                new WithdrawalReceiptExport($withdrawalReceipts),
                'withdrawal_receipts.pdf',
                \Maatwebsite\Excel\Excel::MPDF, [
                    'Content-Type' => 'application/pdf'
                ]
            );
        }

        return Excel::download(
            new WithdrawalReceiptExport($withdrawalReceipts),
            'withdrawal_receipts.xls',
            \Maatwebsite\Excel\Excel::XLS, [
                'Content-Type' => 'text/csv'
            ]
        );
    }
}
