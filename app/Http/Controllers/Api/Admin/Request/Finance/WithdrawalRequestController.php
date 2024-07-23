<?php

namespace App\Http\Controllers\Api\Admin\Request\Finance;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\Admin\Request\Finance\Interfaces\WithdrawalRequestControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\Request\Finance\WithdrawalRequest\IndexRequest;
use App\Repositories\Receipt\WithdrawalRequestRepository;
use App\Services\Withdrawal\WithdrawalRequestService;
use App\Transformers\Api\Admin\Request\Finance\WithdrawalRequest\WithdrawalRequestListPageTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class WithdrawalRequestController
 *
 * @package App\Http\Controllers\Api\Admin\Request\Finance
 */
final class WithdrawalRequestController extends BaseController implements WithdrawalRequestControllerInterface
{
    /**
     * @var WithdrawalRequestRepository
     */
    protected WithdrawalRequestRepository $withdrawalRequestRepository;

    /**
     * @var WithdrawalRequestService
     */
    protected WithdrawalRequestService $withdrawalRequestService;

    /**
     * WithdrawalRequestController constructor
     */
    public function __construct()
    {
        /** @var WithdrawalRequestRepository withdrawalRequestRepository */
        $this->withdrawalRequestRepository = new WithdrawalRequestRepository();

        /** @var WithdrawalRequestService withdrawalRequestService */
        $this->withdrawalRequestService = new WithdrawalRequestService();
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
         * Getting withdrawal requests with pagination
         */
        $withdrawalRequests = $this->withdrawalRequestRepository->getAllFiltered(
            $request->input('request_id'),
            $request->input('user_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('username'),
            $request->input('languages_ids'),
            $request->input('payout_method_id'),
            $request->input('amount'),
            $request->input('user_balance_types_ids'),
            $request->input('request_statuses_ids'),
            $request->input('receipt_statuses_ids'),
            $request->input('admin'),
            $request->input('sort_by'),
            $request->input('sort_order')
        );

        /**
         * Getting withdrawal request statuses
         */
        $withdrawalRequestStatuses = $this->withdrawalRequestService->getAllStatusesWithCounts(
            $withdrawalRequests
        );

        /**
         * Checking pagination existence
         */
        if ($request->input('paginated') === true) {

            /**
             * Paginating withdrawal requests
             */
            $paginatedWithdrawalRequests = paginateCollection(
                $withdrawalRequests,
                $request->input('per_page') ?
                    $request->input('per_page') :
                    $this->withdrawalRequestRepository->getPerPage()
            );

            return $this->setPagination($paginatedWithdrawalRequests)->respondWithSuccess(
                $this->transformItem([],
                    new WithdrawalRequestListPageTransformer(
                        $withdrawalRequestStatuses,
                        new Collection($paginatedWithdrawalRequests->items())
                    )
                )['withdrawal_request_list'],
                trans('validations/api/admin/request/finance/withdrawalRequest/index.result.success')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem([],
                new WithdrawalRequestListPageTransformer(
                    $withdrawalRequestStatuses,
                    $withdrawalRequests
                )
            )['withdrawal_request_list'],
            trans('validations/api/admin/request/finance/withdrawalRequest/index.result.success')
        );
    }
}
