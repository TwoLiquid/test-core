<?php

namespace App\Http\Controllers\Api\Admin\Request\Vybe;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\Admin\Request\Vybe\Interfaces\VybeChangeRequestControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\Request\Vybe\ChangeRequest\IndexRequest;
use App\Repositories\Vybe\VybeChangeRequestRepository;
use App\Services\Vybe\VybeChangeRequestService;
use App\Transformers\Api\Admin\Request\Vybe\ChangeRequest\VybeChangeRequestListPageTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class VybeChangeRequestController
 *
 * @package App\Http\Controllers\Api\Admin\Request\Vybe
 */
final class VybeChangeRequestController extends BaseController implements VybeChangeRequestControllerInterface
{
    /**
     * @var VybeChangeRequestRepository
     */
    protected VybeChangeRequestRepository $vybeChangeRequestRepository;

    /**
     * @var VybeChangeRequestService
     */
    protected VybeChangeRequestService $vybeChangeRequestService;

    /**
     * VybeChangeRequestController constructor
     */
    public function __construct()
    {
        /** @var VybeChangeRequestRepository vybeChangeRequestRepository */
        $this->vybeChangeRequestRepository = new VybeChangeRequestRepository();

        /** @var VybeChangeRequestService vybeChangeRequestService */
        $this->vybeChangeRequestService = new VybeChangeRequestService();
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
         * Getting vybe change requests with pagination
         */
        $vybeChangeRequests = $this->vybeChangeRequestRepository->getAllFiltered(
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
         * Getting vybe change request statuses
         */
        $vybeChangeRequestStatuses = $this->vybeChangeRequestService->getAllStatusesWithCounts(
            $vybeChangeRequests
        );

        /**
         * Checking pagination existence
         */
        if ($request->input('paginated') === true) {

            /**
             * Paginating vybe change requests
             */
            $paginatedVybeChangeRequests = paginateCollection(
                $vybeChangeRequests,
                $request->input('per_page') ?
                    $request->input('per_page') :
                    $this->vybeChangeRequestRepository->getPerPage()
            );

            return $this->setPagination($paginatedVybeChangeRequests)->respondWithSuccess(
                $this->transformItem([],
                    new VybeChangeRequestListPageTransformer(
                        $vybeChangeRequestStatuses,
                        new Collection($paginatedVybeChangeRequests->items())
                    )
                )['vybe_change_request_list'],
                trans('validations/api/admin/request/vybe/changeRequest/index.result.success')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem([],
                new VybeChangeRequestListPageTransformer(
                    $vybeChangeRequestStatuses,
                    $vybeChangeRequests
                )
            )['vybe_change_request_list'],
            trans('validations/api/admin/request/vybe/changeRequest/index.result.success')
        );
    }
}
