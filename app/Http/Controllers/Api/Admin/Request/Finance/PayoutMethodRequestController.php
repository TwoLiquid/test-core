<?php

namespace App\Http\Controllers\Api\Admin\Request\Finance;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\Admin\Request\Finance\Interfaces\PayoutMethodRequestControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\Request\Finance\PayoutMethodRequest\IndexRequest;
use App\Repositories\Payout\PayoutMethodRequestRepository;
use App\Services\Payout\PayoutMethodRequestService;
use App\Transformers\Api\Admin\Request\Finance\PayoutMethodRequest\PayoutMethodRequestListPageTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class PayoutMethodRequestController
 *
 * @package App\Http\Controllers\Api\Admin\Request\Finance
 */
final class PayoutMethodRequestController extends BaseController implements PayoutMethodRequestControllerInterface
{
    /**
     * @var PayoutMethodRequestRepository
     */
    protected PayoutMethodRequestRepository $payoutMethodRequestRepository;

    /**
     * @var PayoutMethodRequestService
     */
    protected PayoutMethodRequestService $payoutMethodRequestService;

    /**
     * PayoutMethodRequestController constructor
     */
    public function __construct()
    {
        /** @var PayoutMethodRequestRepository payoutMethodRequestRepository */
        $this->payoutMethodRequestRepository = new PayoutMethodRequestRepository();

        /** @var PayoutMethodRequestService payoutMethodRequestService */
        $this->payoutMethodRequestService = new PayoutMethodRequestService();
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
         * Getting payout method requests with pagination
         */
        $payoutMethodRequests = $this->payoutMethodRequestRepository->getAllFiltered(
            $request->input('request_id'),
            $request->input('user_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('languages_ids'),
            $request->input('payout_method_id'),
            $request->input('user_balance_types_ids'),
            $request->input('request_statuses_ids'),
            $request->input('admin'),
            $request->input('sort_by'),
            $request->input('sort_order')
        );

        /**
         * Getting payout method request statuses
         */
        $payoutMethodRequestStatuses = $this->payoutMethodRequestService->getAllStatusesWithCounts(
            $payoutMethodRequests
        );

        /**
         * Checking pagination existence
         */
        if ($request->input('paginated') === true) {

            /**
             * Paginating payout method requests
             */
            $paginatedPayoutMethodRequests = paginateCollection(
                $payoutMethodRequests,
                $request->input('per_page') ?
                    $request->input('per_page') :
                    $this->payoutMethodRequestRepository->getPerPage()
            );

            return $this->setPagination($paginatedPayoutMethodRequests)->respondWithSuccess(
                $this->transformItem([],
                    new PayoutMethodRequestListPageTransformer(
                        $payoutMethodRequestStatuses,
                        new Collection($paginatedPayoutMethodRequests->items())
                    )
                )['payout_method_request_list'],
                trans('validations/api/admin/request/finance/payoutMethodRequest/index.result.success')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem([],
                new PayoutMethodRequestListPageTransformer(
                    $payoutMethodRequestStatuses,
                    $payoutMethodRequests
                )
            )['payout_method_request_list'],
            trans('validations/api/admin/request/finance/payoutMethodRequest/index.result.success')
        );
    }
}
