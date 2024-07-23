<?php

namespace App\Http\Controllers\Api\Admin\User\Finance\Buyer;

use App\Exceptions\DatabaseException;
use App\Exports\Api\Admin\User\Finance\Buyer\OrderItemExport;
use App\Http\Controllers\Api\Admin\User\Finance\Buyer\Interfaces\OrderItemControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\User\Finance\Buyer\OrderItem\ExportRequest;
use App\Http\Requests\Api\Admin\User\Finance\Buyer\OrderItem\IndexRequest;
use App\Repositories\Order\OrderItemRepository;
use App\Repositories\User\UserRepository;
use App\Services\Order\OrderItemService;
use App\Services\Vybe\VybeService;
use App\Transformers\Api\Admin\User\Finance\Buyer\OrderItem\OrderItemListPageTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use PhpOffice\PhpSpreadsheet\Exception as PhpSpreadsheetException;
use PhpOffice\PhpSpreadsheet\Writer\Exception as PhpSpreadsheetWriterException;

/**
 * Class OrderItemController
 *
 * @package App\Http\Controllers\Api\Admin\User\Finance\Buyer
 */
class OrderItemController extends BaseController implements OrderItemControllerInterface
{
    /**
     * @var OrderItemRepository
     */
    protected OrderItemRepository $orderItemRepository;

    /**
     * @var OrderItemService
     */
    protected OrderItemService $orderItemService;

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * @var VybeService
     */
    protected VybeService $vybeService;

    /**
     * OrderItemController constructor
     */
    public function __construct()
    {
        /** @var OrderItemRepository orderItemRepository */
        $this->orderItemRepository = new OrderItemRepository();

        /** @var OrderItemService orderItemService */
        $this->orderItemService = new OrderItemService();

        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();

        /** @var VybeService vybeService */
        $this->vybeService = new VybeService();
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
                trans('validations/api/admin/user/finance/buyer/orderItem/index.result.error.find')
            );
        }

        /**
         * Getting order items with pagination
         */
        $orderItems = $this->orderItemRepository->getAllForBuyerFiltered(
            $user,
            $request->input('order_item_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('seller'),
            $request->input('vybe_version'),
            $request->input('vybe_types_ids'),
            $request->input('order_item_statuses_ids'),
            $request->input('order_item_payment_statuses_ids'),
            $request->input('sort_by'),
            $request->input('sort_order')
        );

        /**
         * Getting order items for labels
         */
        $orderItemsForLabels = $this->orderItemRepository->getAllForBuyerFilteredForAdminLabels(
            $user,
            $request->input('order_item_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('seller'),
            $request->input('vybe_version')
        );

        /**
         * Getting order item statuses with counts
         */
        $orderItemStatuses = $this->orderItemService->getForAdminStatusesByIds(
            $orderItemsForLabels
        );

        /**
         * Getting order item payment statuses with counts
         */
        $orderItemPaymentStatuses = $this->orderItemService->getForAdminPaymentStatusesByIds(
            $orderItemsForLabels
        );

        /**
         * Getting vybe types with counts
         */
        $vybeTypes = $this->vybeService->getForAdminTypesByOrderItemsIds(
            $orderItemsForLabels
        );

        /**
         * Checking pagination existence
         */
        if ($request->input('paginated') === true) {

            /**
             * Paginating order items
             */
            $paginatedOrderItems = paginateCollection(
                $orderItems,
                $request->input('per_page') ?
                    $request->input('per_page') :
                    $this->orderItemRepository->getPerPage()
            );

            return $this->setPagination($paginatedOrderItems)->respondWithSuccess(
                $this->transformItem([],
                    new OrderItemListPageTransformer(
                        new Collection($paginatedOrderItems->items()),
                        $vybeTypes,
                        $orderItemStatuses,
                        $orderItemPaymentStatuses
                    )
                )['order_item_list'],
                trans('validations/api/admin/user/finance/buyer/orderItem/index.result.success')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem([],
                new OrderItemListPageTransformer(
                    $orderItems,
                    $vybeTypes,
                    $orderItemStatuses,
                    $orderItemPaymentStatuses
                )
            )['order_item_list'],
            trans('validations/api/admin/user/finance/buyer/orderItem/index.result.success')
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
         * Getting order items
         */
        $orderItems = $this->orderItemRepository->getAllForBuyerFiltered(
            $user,
            $request->input('order_item_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('seller'),
            $request->input('vybe_version'),
            $request->input('vybe_types_ids'),
            $request->input('order_item_statuses_ids'),
            $request->input('order_item_payment_statuses_ids'),
            $request->input('sort_by'),
            $request->input('sort_order')
        );

        /**
         * Checking an exporting file type
         */
        if ($type == 'pdf') {
            return Excel::download(
                new OrderItemExport($orderItems),
                'order_items.pdf',
                \Maatwebsite\Excel\Excel::MPDF, [
                    'Content-Type' => 'application/pdf'
                ]
            );
        }

        return Excel::download(
            new OrderItemExport($orderItems),
            'order_items.xls',
            \Maatwebsite\Excel\Excel::XLS, [
                'Content-Type' => 'text/csv'
            ]
        );
    }
}
