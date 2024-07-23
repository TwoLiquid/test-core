<?php

namespace App\Http\Controllers\Api\Admin\Request\Finance;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\Admin\Request\Finance\Interfaces\BillingChangeRequestControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\Request\Finance\BillingChangeRequest\IndexRequest;
use App\Repositories\Billing\BillingChangeRequestRepository;
use App\Services\Billing\BillingChangeRequestService;
use App\Transformers\Api\Admin\Request\Finance\BillingChangeRequest\BillingChangeRequestListPageTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class BillingChangeRequestController
 *
 * @package App\Http\Controllers\Api\Admin\Request\Finance
 */
final class BillingChangeRequestController extends BaseController implements BillingChangeRequestControllerInterface
{
    /**
     * @var BillingChangeRequestRepository
     */
    protected BillingChangeRequestRepository $billingChangeRequestRepository;

    /**
     * @var BillingChangeRequestService
     */
    protected BillingChangeRequestService $billingChangeRequestService;

    /**
     * BillingChangeRequestController constructor
     */
    public function __construct()
    {
        /** @var BillingChangeRequestRepository billingChangeRequestRepository */
        $this->billingChangeRequestRepository = new BillingChangeRequestRepository();

        /** @var BillingChangeRequestService billingChangeRequestService */
        $this->billingChangeRequestService = new BillingChangeRequestService();
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
         * Getting billing change requests with pagination
         */
        $billingChangeRequests = $this->billingChangeRequestRepository->getAllFiltered(
            $request->input('request_id'),
            $request->input('user_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('username'),
            $request->input('old_country'),
            $request->input('new_country'),
            $request->input('languages_ids'),
            $request->input('user_balance_types_ids'),
            $request->input('user_statuses_ids'),
            $request->input('request_statuses_ids'),
            $request->input('admin'),
            $request->input('sort_by'),
            $request->input('sort_order')
        );

        /**
         * Getting billing change request statuses
         */
        $billingChangeRequestStatuses = $this->billingChangeRequestService->getAllStatusesWithCounts(
            $billingChangeRequests
        );

        /**
         * Checking pagination existence
         */
        if ($request->input('paginated') === true) {

            /**
             * Paginating billing setting requests
             */
            $paginatedBillingChangeRequests = paginateCollection(
                $billingChangeRequests,
                $request->input('per_page') ?
                    $request->input('per_page') :
                    $this->billingChangeRequestRepository->getPerPage()
            );

            return $this->setPagination($paginatedBillingChangeRequests)->respondWithSuccess(
                $this->transformItem([],
                    new BillingChangeRequestListPageTransformer(
                        $billingChangeRequestStatuses,
                        new Collection($paginatedBillingChangeRequests->items())
                    )
                )['billing_change_request_list'],
                trans('validations/api/admin/request/finance/billingChangeRequest/index.result.success')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem([],
                new BillingChangeRequestListPageTransformer(
                    $billingChangeRequestStatuses,
                    $billingChangeRequests
                )
            )['billing_change_request_list'],
            trans('validations/api/admin/request/finance/billingChangeRequest/index.result.success')
        );
    }
}
