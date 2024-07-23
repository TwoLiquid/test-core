<?php

namespace App\Http\Controllers\Api\Admin\User\Finance\Buyer;

use App\Exceptions\DatabaseException;
use App\Exports\Api\Admin\User\Finance\Buyer\AddFundsReceiptExport;
use App\Http\Controllers\Api\Admin\User\Finance\Buyer\Interfaces\AddFundsReceiptControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\User\Finance\Buyer\AddFunds\Receipt\ExportRequest;
use App\Http\Requests\Api\Admin\User\Finance\Buyer\AddFunds\Receipt\IndexRequest;
use App\Repositories\Payment\PaymentMethodRepository;
use App\Repositories\Receipt\AddFundsReceiptRepository;
use App\Repositories\User\UserRepository;
use App\Services\AddFunds\AddFundsReceiptService;
use App\Transformers\Api\Admin\User\Finance\Buyer\AddFunds\Receipt\AddFundsReceiptListPageTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use PhpOffice\PhpSpreadsheet\Exception as PhpSpreadsheetException;
use PhpOffice\PhpSpreadsheet\Writer\Exception as PhpSpreadsheetWriterException;

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
     * @var PaymentMethodRepository
     */
    protected PaymentMethodRepository $paymentMethodRepository;

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * AddFundsReceiptController constructor
     */
    public function __construct()
    {
        /** @var AddFundsReceiptRepository addFundsReceiptRepository */
        $this->addFundsReceiptRepository = new AddFundsReceiptRepository();

        /** @var AddFundsReceiptService addFundsReceiptService */
        $this->addFundsReceiptService = new AddFundsReceiptService();

        /** @var PaymentMethodRepository paymentMethodRepository */
        $this->paymentMethodRepository = new PaymentMethodRepository();

        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();
    }

    /**
     * @param int $id
     * @param IndexRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function index(
        int $id,
        IndexRequest $request
    ) : JsonResponse
    {
        /**
         * Getting user
         */
        $user = $this->userRepository->findByIdForAdmin($id);

        if (!$user) {
            return $this->respondWithError(
                trans('validations/api/admin/user/finance/buyer/addFunds/receipt/index.result.error.find')
            );
        }

        /**
         * Getting add funds receipts
         */
        $addFundsReceipts = $this->addFundsReceiptRepository->getFilteredForUser(
            $user,
            $request->input('receipt_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('method_id'),
            $request->input('statuses_ids'),
            $request->input('sort_by'),
            $request->input('sort_order')
        );

        /**
         * Getting add funds receipts for labels
         */
        $addFundsReceiptsForLabels = $this->addFundsReceiptRepository->getFilteredForUserForAdminLabels(
            $user,
            $request->input('receipt_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('method_id')
        );

        /**
         * Getting add funds receipt statuses with counts
         */
        $addFundsReceiptStatuses = $this->addFundsReceiptService->getForAdminStatusesByIds(
            $addFundsReceiptsForLabels
        );

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
                        $addFundsReceiptStatuses
                    )
                )['add_funds_receipt_list'],
                trans('validations/api/admin/user/finance/buyer/addFunds/receipt/index.result.success')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem([],
                new AddFundsReceiptListPageTransformer(
                    $addFundsReceipts,
                    $addFundsReceiptStatuses
                )
            )['add_funds_receipt_list'],
            trans('validations/api/admin/user/finance/buyer/addFunds/receipt/index.result.success')
        );
    }

    /**
     * @param int $id
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
        int $id,
        string $type,
        ExportRequest $request
    ) : BinaryFileResponse
    {
        /**
         * Getting user
         */
        $user = $this->userRepository->findByIdForAdmin($id);

        /**
         * Getting add funds receipts
         */
        $addFundsReceipts = $this->addFundsReceiptRepository->getFilteredForUser(
            $user,
            $request->input('receipt_id'),
            $request->input('date_from'),
            $request->input('date_to'),
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
}
