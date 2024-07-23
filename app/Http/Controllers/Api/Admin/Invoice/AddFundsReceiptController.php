<?php

namespace App\Http\Controllers\Api\Admin\Invoice;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Exports\Api\Admin\Invoice\AddFundsReceiptExport;
use App\Http\Controllers\Api\Admin\Invoice\Interfaces\AddFundsReceiptControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\Invoice\AddFunds\Receipt\AddPaymentRequest;
use App\Http\Requests\Api\Admin\Invoice\AddFunds\Receipt\IndexRequest;
use App\Http\Requests\Api\Admin\Invoice\AddFunds\Receipt\ExportRequest;
use App\Repositories\Payment\PaymentMethodRepository;
use App\Repositories\Receipt\AddFundsReceiptRepository;
use App\Repositories\Receipt\AddFundsTransactionRepository;
use App\Services\AddFunds\AddFundsReceiptService;
use App\Services\File\PdfService;
use App\Transformers\Api\Admin\Invoice\AddFunds\AddFundsReceiptListPageTransformer;
use App\Transformers\Api\Admin\Invoice\AddFunds\AddFundsReceiptPageTransformer;
use App\Transformers\Api\Admin\Invoice\AddFunds\PaymentMethodTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\MpdfException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use PhpOffice\PhpSpreadsheet\Exception as PhpSpreadsheetException;
use PhpOffice\PhpSpreadsheet\Writer\Exception as PhpSpreadsheetWriterException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AddFundsReceiptController
 *
 * @package App\Http\Controllers\Api\Admin\Invoice
 */
class AddFundsReceiptController extends BaseController implements AddFundsReceiptControllerInterface
{
    /**
     * @var AddFundsReceiptRepository
     */
    protected AddFundsReceiptRepository $addFundsReceiptRepository;

    /**
     * @var AddFundsReceiptService
     */
    protected AddFundsReceiptService $addFundsReceiptService;

    /**
     * @var PdfService
     */
    protected PdfService $pdfService;

    /**
     * @var AddFundsTransactionRepository
     */
    protected AddFundsTransactionRepository $addFundsTransactionRepository;

    /**
     * @var PaymentMethodRepository
     */
    protected PaymentMethodRepository $paymentMethodRepository;

    /**
     * AddFundsReceiptController constructor
     */
    public function __construct()
    {
        /** @var AddFundsReceiptRepository addFundsReceiptRepository */
        $this->addFundsReceiptRepository = new AddFundsReceiptRepository();

        /** @var AddFundsReceiptService addFundsReceiptService */
        $this->addFundsReceiptService = new AddFundsReceiptService();

        /** @var AddFundsTransactionRepository addFundsTransactionRepository */
        $this->addFundsTransactionRepository = new AddFundsTransactionRepository();

        /** @var PaymentMethodRepository paymentMethodRepository */
        $this->paymentMethodRepository = new PaymentMethodRepository();

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
        $addFundsReceipts = $this->addFundsReceiptRepository->getFiltered(
            $request->input('receipt_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('buyer'),
            $request->input('method_id'),
            $request->input('statuses_ids'),
            $request->input('sort_by'),
            $request->input('sort_order')
        );

        /**
         * Getting add funds receipts for labels
         */
        $addFundsReceiptsForLabels = $this->addFundsReceiptRepository->getFilteredForAdminLabels(
            $request->input('receipt_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('buyer'),
            $request->input('method_id')
        );

        /**
         * Getting add funds receipt statuses with counts
         */
        $addFundsReceiptStatuses = $this->addFundsReceiptService->getForAdminStatusesByIds(
            $addFundsReceiptsForLabels
        );

        /**
         * Getting payment methods
         */
        $paymentMethods = $this->paymentMethodRepository->getAll();

        /**
         * Checking pagination existence
         */
        if ($request->input('paginated') === true) {

            /**
             * Paginating add funds receipts
             */
            $paginatedAddFundsReceipts = paginateCollection(
                $addFundsReceipts,
                $request->input('per_page') ?
                    $request->input('per_page') :
                    $this->addFundsReceiptRepository->getPerPage()
            );

            return $this->setPagination($paginatedAddFundsReceipts)->respondWithSuccess(
                $this->transformItem([],
                    new AddFundsReceiptListPageTransformer(
                        new Collection($paginatedAddFundsReceipts->items()),
                        $addFundsReceiptStatuses,
                        $paymentMethods
                    )
                )['add_funds_receipt_list'],
                trans('validations/api/admin/invoice/addFunds/receipt/index.result.success')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem([],
                new AddFundsReceiptListPageTransformer(
                    $addFundsReceipts,
                    $addFundsReceiptStatuses,
                    $paymentMethods
                )
            )['add_funds_receipt_list'],
            trans('validations/api/admin/invoice/addFunds/receipt/index.result.success')
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
         * Getting add funds receipt
         */
        $addFundsReceipt = $this->addFundsReceiptRepository->findFullById($id);

        /**
         * Checking add funds receipt existence
         */
        if (!$addFundsReceipt) {
            $this->respondWithError(
                trans('validations/api/admin/invoice/addFunds/receipt/show.result.error')
            );
        }

        /**
         * Getting payment methods
         */
        $paymentMethods = $this->paymentMethodRepository->getAll();

        return $this->respondWithSuccess(
            array_merge(
                $this->transformItem($addFundsReceipt, new AddFundsReceiptPageTransformer),
                $this->transformCollection($paymentMethods, new PaymentMethodTransformer),
            ), trans('validations/api/admin/invoice/addFunds/receipt/show.result.success')
        );
    }

    /**
     * @param int $id
     * @param AddPaymentRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function addPayment(
        int $id,
        AddPaymentRequest $request
    ) : JsonResponse
    {
        /**
         * Getting add funds receipt
         */
        $addFundsReceipt = $this->addFundsReceiptRepository->findFullById($id);

        /**
         * Checking add funds receipt existence
         */
        if (!$addFundsReceipt) {
            return $this->respondWithError(
                trans('validations/api/admin/invoice/addFunds/receipt/addPayment.result.error.find')
            );
        }

        /**
         * Checking add funds status
         */
        if (!$addFundsReceipt->getStatus()->isUnpaid()) {
            return $this->respondWithError(
                trans('validations/api/admin/invoice/addFunds/receipt/addPayment.result.error.status')
            );
        }

        /**
         * Checking amount is able to be paid
         */
        if (!$this->addFundsReceiptService->isAvailableToAddPayment(
            $addFundsReceipt,
            $request->input('amount')
        )) {
            return $this->respondWithError(
                trans('validations/api/admin/invoice/addFunds/receipt/addPayment.result.error.amount')
            );
        }

        /**
         * Getting payment method
         */
        $paymentMethod = $this->paymentMethodRepository->findById(
            $request->input('method_id')
        );

        /**
         * Creating add funds transaction
         */
        $this->addFundsTransactionRepository->store(
            $addFundsReceipt,
            $paymentMethod,
            $request->input('external_id'),
            $request->input('amount'),
            $request->input('transaction_fee'),
            null,
            $request->input('payment_date')
        );

        return $this->respondWithSuccess(
            $this->transformItem(
                $this->addFundsReceiptRepository->findFullById(
                    $addFundsReceipt->id
                ), new AddFundsReceiptPageTransformer
            ), trans('validations/api/admin/invoice/addFunds/receipt/addPayment.result.success')
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
         * Getting add funds receipts
         */
        $addFundsReceipts = $this->addFundsReceiptRepository->getFiltered(
            $request->input('receipt_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('buyer'),
            $request->input('method_id'),
            $request->input('statuses_ids'),
            $request->input('sort_by'),
            $request->input('sort_order')
        );

        /**
         * Checking an exporting file type
         */
        if ($type == 'pdf') {
            return Excel::download(
                new AddFundsReceiptExport($addFundsReceipts),
                'add_funds_receipts.pdf',
                \Maatwebsite\Excel\Excel::MPDF, [
                    'Content-Type' => 'application/pdf'
                ]
            );
        }

        return Excel::download(
            new AddFundsReceiptExport($addFundsReceipts),
            'add_funds_receipts.xls',
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
         * Getting add funds receipt
         */
        $addFundsReceipt = $this->addFundsReceiptRepository->findFullById($id);

        /**
         * Checking withdrawal receipt existence
         */
        if (!$addFundsReceipt) {
            return $this->respondWithError(
                trans('validations/api/admin/invoice/addFunds/receipt/viewPdf.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($addFundsReceipt, new AddFundsReceiptPageTransformer),
            trans('validations/api/admin/invoice/addFunds/receipt/viewPdf.result.success')
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
                trans('validations/api/admin/invoice/addFunds/receipt/downloadPdf.result.error.find'),
                null,
                422
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
