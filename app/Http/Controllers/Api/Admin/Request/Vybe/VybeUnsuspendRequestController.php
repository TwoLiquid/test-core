<?php

namespace App\Http\Controllers\Api\Admin\Request\Vybe;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\Admin\Request\Vybe\Interfaces\VybeUnsuspendRequestControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\Request\Vybe\UnsuspendRequest\IndexRequest;
use App\Repositories\Vybe\VybeUnsuspendRequestRepository;
use App\Services\Vybe\VybeUnsuspendRequestService;
use App\Transformers\Api\Admin\Request\Vybe\UnsuspendRequest\VybeUnsuspendRequestListPageTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class VybeUnsuspendRequestController
 *
 * @package App\Http\Controllers\Api\Admin\Request\Vybe
 */
final class VybeUnsuspendRequestController extends BaseController implements VybeUnsuspendRequestControllerInterface
{
    /**
     * @var VybeUnsuspendRequestRepository
     */
    protected VybeUnsuspendRequestRepository $vybeUnsuspendRequestRepository;

    /**
     * @var VybeUnsuspendRequestService
     */
    protected VybeUnsuspendRequestService $vybeUnsuspendRequestService;

    /**
     * VybeUnsuspendRequestController constructor
     */
    public function __construct()
    {
        /** @var VybeUnsuspendRequestRepository vybeUnsuspendRequestRepository */
        $this->vybeUnsuspendRequestRepository = new VybeUnsuspendRequestRepository();

        /** @var VybeUnsuspendRequestService vybeUnsuspendRequestService */
        $this->vybeUnsuspendRequestService = new VybeUnsuspendRequestService();
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
         * Getting vybe unsuspend requests with pagination
         */
        $vybeUnsuspendRequests = $this->vybeUnsuspendRequestRepository->getAllFiltered(
            $request->input('request_id'),
            $request->input('vybe_version'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('username'),
            $request->input('languages_ids'),
            $request->input('sales'),
            $request->input('vybe_statuses_ids'),
            $request->input('request_statuses_ids'),
            $request->input('admin'),
            $request->input('sort_by'),
            $request->input('sort_order')
        );

        /**
         * Getting vybe unsuspend request statuses
         */
        $vybeUnsuspendRequestStatuses = $this->vybeUnsuspendRequestService->getAllStatusesWithCounts(
            $vybeUnsuspendRequests
        );

        /**
         * Checking pagination existence
         */
        if ($request->input('paginated') === true) {

            /**
             * Paginating vybe unsuspend requests
             */
            $paginatedVybeUnsuspendRequests = paginateCollection(
                $vybeUnsuspendRequests,
                $request->input('per_page') ?
                    $request->input('per_page') :
                    $this->vybeUnsuspendRequestRepository->getPerPage()
            );

            return $this->setPagination($paginatedVybeUnsuspendRequests)->respondWithSuccess(
                $this->transformItem([],
                    new VybeUnsuspendRequestListPageTransformer(
                        $vybeUnsuspendRequestStatuses,
                        new Collection($paginatedVybeUnsuspendRequests->items())
                    )
                )['vybe_unsuspend_request_list'],
                trans('validations/api/admin/request/vybe/unsuspendRequest/index.result.success')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem([],
                new VybeUnsuspendRequestListPageTransformer(
                    $vybeUnsuspendRequestStatuses,
                    $vybeUnsuspendRequests
                )
            )['vybe_unsuspend_request_list'],
            trans('validations/api/admin/request/vybe/unsuspendRequest/index.result.success')
        );
    }
}
