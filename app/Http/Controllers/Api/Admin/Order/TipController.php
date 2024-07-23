<?php

namespace App\Http\Controllers\Api\Admin\Order;

use App\Exceptions\DatabaseException;
use App\Exports\Api\Admin\Order\TipExport;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Admin\Order\Interfaces\TipControllerInterface;
use App\Http\Requests\Api\Admin\Order\Tip\ExportRequest;
use App\Http\Requests\Api\Admin\Order\Tip\IndexRequest;
use App\Repositories\Tip\TipRepository;
use App\Services\Tip\TipInvoiceService;
use App\Services\Vybe\VybeService;
use App\Transformers\Api\Admin\Order\Tip\TipListPageTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use PhpOffice\PhpSpreadsheet\Exception as PhpSpreadsheetException;
use PhpOffice\PhpSpreadsheet\Writer\Exception as PhpSpreadsheetWriterException;

/**
 * Class TipController
 *
 * @package App\Http\Controllers\Api\Admin\Order
 */
class TipController extends BaseController implements TipControllerInterface
{
    /**
     * @var TipInvoiceService
     */
    protected TipInvoiceService $tipInvoiceService;

    /**
     * @var TipRepository
     */
    protected TipRepository $tipRepository;

    /**
     * @var VybeService
     */
    protected VybeService $vybeService;

    /**
     * TipController constructor
     */
    public function __construct()
    {
        /** @var TipInvoiceService tipInvoiceService */
        $this->tipInvoiceService = new TipInvoiceService();

        /** @var TipRepository tipRepository */
        $this->tipRepository = new TipRepository();

        /** @var VybeService vybeService */
        $this->vybeService = new VybeService();
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
         * Getting tips with pagination
         */
        $tips = $this->tipRepository->getAllFiltered(
            $request->input('item_id'),
            $request->input('vybe_types_ids'),
            $request->input('buyer'),
            $request->input('seller'),
            $request->input('payment_methods_ids'),
            $request->input('order_item_statuses_ids'),
            $request->input('tip_invoice_buyer_id'),
            $request->input('tip_invoice_buyer_statuses_ids'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('tip_invoice_seller_id'),
            $request->input('tip_invoice_seller_statuses_ids'),
            $request->input('sort_by'),
            $request->input('sort_order')
        );

        /**
         * Getting tips for labels
         */
        $tipsForLabels = $this->tipRepository->getAllFilteredForAdminLabels(
            $request->input('item_id'),
            $request->input('buyer'),
            $request->input('seller'),
            $request->input('payment_methods_ids'),
            $request->input('order_item_statuses_ids'),
            $request->input('tip_invoice_buyer_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('tip_invoice_seller_id')
        );

        /**
         * Getting vybe types with counts
         */
        $vybeTypes = $this->vybeService->getForAdminTypesByTipsIds(
            $tipsForLabels
        );

        /**
         * Getting tip buyer invoice statuses with counts
         */
        $tipInvoiceBuyerStatuses = $this->tipInvoiceService->getForAdminBuyerStatusesByTipsIds(
            $tipsForLabels
        );

        /**
         * Getting tip seller invoice statuses with counts
         */
        $tipInvoiceSellerStatuses = $this->tipInvoiceService->getForAdminSellerStatusesByTipsIds(
            $tipsForLabels
        );

        /**
         * Checking pagination existence
         */
        if ($request->input('paginated') === true) {

            /**
             * Paginating tips
             */
            $paginatedOrders = paginateCollection(
                $tips,
                $request->input('per_page') ?
                    $request->input('per_page') :
                    $this->tipRepository->getPerPage()
            );

            return $this->setPagination($paginatedOrders)->respondWithSuccess(
                $this->transformItem([],
                    new TipListPageTransformer(
                        new Collection($paginatedOrders->items()),
                        $vybeTypes,
                        $tipInvoiceBuyerStatuses,
                        $tipInvoiceSellerStatuses
                    )
                )['tip_list'],
                trans('validations/api/admin/order/tip/index.result.success')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem([],
                new TipListPageTransformer(
                    $tips,
                    $vybeTypes,
                    $tipInvoiceBuyerStatuses,
                    $tipInvoiceSellerStatuses
                )
            )['tip_list'],
            trans('validations/api/admin/order/tip/index.result.success')
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
         * Getting tips with pagination
         */
        $tips = $this->tipRepository->getAllFiltered(
            $request->input('item_id'),
            $request->input('vybe_types_ids'),
            $request->input('buyer'),
            $request->input('seller'),
            $request->input('payment_methods_ids'),
            $request->input('order_item_statuses_ids'),
            $request->input('tip_invoice_buyer_id'),
            $request->input('tip_invoice_buyer_statuses_ids'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('tip_invoice_seller_id'),
            $request->input('tip_invoice_seller_statuses_ids'),
            $request->input('sort_by'),
            $request->input('sort_order')
        );

        /**
         * Checking an exporting file type
         */
        if ($type == 'pdf') {
            return Excel::download(
                new TipExport($tips),
                'tips.pdf',
                \Maatwebsite\Excel\Excel::MPDF, [
                    'Content-Type' => 'application/pdf'
                ]
            );
        }

        return Excel::download(
            new TipExport($tips),
            'tips.xls',
            \Maatwebsite\Excel\Excel::XLS, [
                'Content-Type' => 'text/csv'
            ]
        );
    }
}
