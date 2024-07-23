<?php

namespace App\Http\Controllers\Api\Admin\User\Finance\Seller;

use App\Exceptions\DatabaseException;
use App\Exports\Api\Admin\User\Finance\Seller\WithdrawalReceiptExport;
use App\Http\Controllers\Api\Admin\User\Finance\Seller\Interfaces\WithdrawalReceiptControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\User\Finance\Seller\Withdrawal\Receipt\ExportRequest;
use App\Http\Requests\Api\Admin\User\Finance\Seller\Withdrawal\Receipt\IndexRequest;
use App\Repositories\Receipt\WithdrawalReceiptRepository;
use App\Repositories\User\UserRepository;
use App\Services\Withdrawal\WithdrawalReceiptService;
use App\Transformers\Api\Admin\User\Finance\Seller\Withdrawal\Receipt\WithdrawalReceiptListPageTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use PhpOffice\PhpSpreadsheet\Exception as PhpSpreadsheetException;
use PhpOffice\PhpSpreadsheet\Writer\Exception as PhpSpreadsheetWriterException;

/**
 * Class WithdrawalReceiptController
 *
 * @package App\Http\Controllers\Api\Admin\User\Finance\Seller
 */
class WithdrawalReceiptController extends BaseController implements WithdrawalReceiptControllerInterface
{
    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * @var WithdrawalReceiptRepository
     */
    protected WithdrawalReceiptRepository $withdrawalReceiptRepository;

    /**
     * @var WithdrawalReceiptService
     */
    protected WithdrawalReceiptService $withdrawalReceiptService;

    /**
     * WithdrawalReceiptController constructor
     */
    public function __construct()
    {
        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();

        /** @var WithdrawalReceiptRepository withdrawalReceiptRepository */
        $this->withdrawalReceiptRepository = new WithdrawalReceiptRepository();

        /** @var WithdrawalReceiptService withdrawalReceiptService */
        $this->withdrawalReceiptService = new WithdrawalReceiptService();
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
                trans('validations/api/admin/user/finance/seller/withdrawal/receipt/index.result.error.find')
            );
        }

        /**
         * Getting withdrawal receipts with pagination
         */
        $withdrawalReceipts = $this->withdrawalReceiptRepository->getAllFilteredForUser(
            $user,
            $request->input('receipt_id'),
            $request->input('request_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('payout_method_id'),
            $request->input('receipt_statuses_ids'),
            $request->input('sort_by'),
            $request->input('sort_order')
        );

        /**
         * Getting withdrawal receipts for labels
         */
        $withdrawalReceiptsForLabels = $this->withdrawalReceiptRepository->getAllFilteredForUserForAdminLabels(
            $user,
            $request->input('receipt_id'),
            $request->input('request_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('payout_method_id')
        );

        /**
         * Getting withdrawal receipt statuses with counts
         */
        $withdrawalReceiptStatuses = $this->withdrawalReceiptService->getForAdminStatusesByIds(
            $withdrawalReceiptsForLabels
        );

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
                        $withdrawalReceiptStatuses
                    )
                )['withdrawal_receipt_list'],
                trans('validations/api/admin/user/finance/seller/withdrawal/receipt/index.result.success')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem([],
                new WithdrawalReceiptListPageTransformer(
                    $withdrawalReceipts,
                    $withdrawalReceiptStatuses
                )
            )['withdrawal_receipt_list'],
            trans('validations/api/admin/user/finance/seller/withdrawal/receipt/index.result.success')
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
         * Getting withdrawal receipts
         */
        $withdrawalReceipts = $this->withdrawalReceiptRepository->getAllFilteredForUser(
            $user,
            $request->input('receipt_id'),
            $request->input('request_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('payout_method_id'),
            $request->input('receipt_statuses_ids'),
            $request->input('sort_by'),
            $request->input('sort_order')
        );

        /**
         * Checking an exporting file type
         */
        if ($type == 'pdf') {
            Excel::store(
                new WithdrawalReceiptExport($withdrawalReceipts),
                'withdrawal_receipts.pdf',
                'public',
                \Maatwebsite\Excel\Excel::MPDF
            );

            return Excel::download(
                new WithdrawalReceiptExport($withdrawalReceipts),
                'withdrawal_receipts.pdf',
                \Maatwebsite\Excel\Excel::MPDF, [
                    'Content-Type' => 'application/pdf'
                ]
            );
        }

        Excel::store(
            new WithdrawalReceiptExport($withdrawalReceipts),
            'withdrawal_receipts.xls',
            'public',
            \Maatwebsite\Excel\Excel::XLS
        );

        return Excel::download(
            new WithdrawalReceiptExport($withdrawalReceipts),
            'withdrawal_receipts.xls',
            \Maatwebsite\Excel\Excel::XLS, [
                'Content-Type' => 'text/csv'
            ]
        );
    }
}
