<?php

namespace App\Http\Controllers\Api\Admin\User\Finance\Buyer;

use App\Exceptions\DatabaseException;
use App\Exports\Api\Admin\User\Finance\Buyer\TipInvoiceExport;
use App\Http\Controllers\Api\Admin\User\Finance\Buyer\Interfaces\TipInvoiceControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\User\Finance\Buyer\Tip\ExportRequest;
use App\Http\Requests\Api\Admin\User\Finance\Buyer\Tip\IndexRequest;
use App\Repositories\Tip\TipInvoiceRepository;
use App\Repositories\User\UserRepository;
use App\Services\Order\OrderItemService;
use App\Services\Tip\TipInvoiceService;
use App\Transformers\Api\Admin\User\Finance\Buyer\Tip\TipInvoiceListPageTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use PhpOffice\PhpSpreadsheet\Exception as PhpSpreadsheetException;
use PhpOffice\PhpSpreadsheet\Writer\Exception as PhpSpreadsheetWriterException;

/**
 * Class TipInvoiceController
 *
 * @package App\Http\Controllers\Api\Admin\User\Finance\Buyer
 */
class TipInvoiceController extends BaseController implements TipInvoiceControllerInterface
{
    /**
     * @var OrderItemService
     */
    protected OrderItemService $orderItemService;

    /**
     * @var TipInvoiceRepository
     */
    protected TipInvoiceRepository $tipInvoiceRepository;

    /**
     * @var TipInvoiceService
     */
    protected TipInvoiceService $tipInvoiceService;

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * TipInvoiceController constructor
     */
    public function __construct()
    {
        /** @var OrderItemService orderItemService */
        $this->orderItemService = new OrderItemService();

        /** @var TipInvoiceRepository tipInvoiceRepository */
        $this->tipInvoiceRepository = new TipInvoiceRepository();

        /** @var TipInvoiceService tipInvoiceService */
        $this->tipInvoiceService = new TipInvoiceService();

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
                trans('validations/api/admin/user/finance/buyer/tip/index.result.error.find')
            );
        }

        /**
         * Getting tip buyer invoices with pagination
         */
        $tipBuyerInvoices = $this->tipInvoiceRepository->getAllFilteredForBuyer(
            $user,
            $request->input('item_id'),
            $request->input('vybe_type_id'),
            $request->input('seller'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('order_item_statuses_ids'),
            $request->input('invoice_id'),
            $request->input('invoice_statuses_ids'),
            $request->input('sort_by'),
            $request->input('sort_order')
        );

        /**
         * Getting tip buyer invoices for labels
         */
        $tipBuyerInvoicesForLabels = $this->tipInvoiceRepository->getAllFilteredForBuyerForAdminLabels(
            $user,
            $request->input('item_id'),
            $request->input('vybe_type_id'),
            $request->input('seller'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('invoice_id')
        );

        /**
         * Getting order item payment statuses with counts
         */
        $orderItemPaymentStatuses = $this->orderItemService->getForAdminStatusesByTipInvoicesIds(
            $tipBuyerInvoicesForLabels
        );

        /**
         * Getting tip buyer invoice statuses with counts
         */
        $tipInvoiceStatuses = $this->tipInvoiceService->getForAdminBuyerStatusesByIds(
            $tipBuyerInvoicesForLabels
        );

        /**
         * Checking pagination existence
         */
        if ($request->input('paginated') === true) {

            /**
             * Paginating tip buyer invoices
             */
            $paginatedTipBuyerInvoices = paginateCollection(
                $tipBuyerInvoices,
                $request->input('per_page') ?
                    $request->input('per_page') :
                    $this->tipInvoiceRepository->getPerPage()
            );

            return $this->setPagination($paginatedTipBuyerInvoices)->respondWithSuccess(
                $this->transformItem([],
                    new TipInvoiceListPageTransformer(
                        new Collection($paginatedTipBuyerInvoices->items()),
                        $orderItemPaymentStatuses,
                        $tipInvoiceStatuses
                    )
                )['tip_buyer_invoice_list'],
                trans('validations/api/admin/user/finance/buyer/tip/index.result.success')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem([],
                new TipInvoiceListPageTransformer(
                    $tipBuyerInvoices,
                    $orderItemPaymentStatuses,
                    $tipInvoiceStatuses
                )
            )['tip_buyer_invoice_list'],
            trans('validations/api/admin/user/finance/buyer/tip/index.result.success')
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
         * Getting tip buyer invoices
         */
        $tipBuyerInvoices = $this->tipInvoiceRepository->getAllFilteredForBuyer(
            $user,
            $request->input('item_id'),
            $request->input('vybe_type_id'),
            $request->input('seller'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('order_item_statuses_ids'),
            $request->input('invoice_id'),
            $request->input('invoice_statuses_ids'),
            $request->input('sort_by'),
            $request->input('sort_order')
        );

        /**
         * Checking an exporting file type
         */
        if ($type == 'pdf') {
            return Excel::download(
                new TipInvoiceExport($tipBuyerInvoices),
                'buyer_tip_invoices.pdf',
                \Maatwebsite\Excel\Excel::MPDF, [
                    'Content-Type' => 'application/pdf'
                ]
            );
        }

        return Excel::download(
            new TipInvoiceExport($tipBuyerInvoices),
            'buyer_tip_invoices.xls',
            \Maatwebsite\Excel\Excel::XLS, [
                'Content-Type' => 'text/csv'
            ]
        );
    }
}
