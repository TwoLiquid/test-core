<?php

namespace App\Http\Controllers\Api\Admin\Request\Vybe;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\Admin\Request\Vybe\Interfaces\VybeDeletionRequestControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\Request\Vybe\DeletionRequest\IndexRequest;
use App\Repositories\Vybe\VybeDeletionRequestRepository;
use App\Services\Vybe\VybeDeletionRequestService;
use App\Transformers\Api\Admin\Request\Vybe\DeletionRequest\VybeDeletionRequestListPageTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class VybeDeletionRequestController
 *
 * @package App\Http\Controllers\Api\Admin\Request\Vybe
 */
final class VybeDeletionRequestController extends BaseController implements VybeDeletionRequestControllerInterface
{
    /**
     * @var VybeDeletionRequestRepository
     */
    protected VybeDeletionRequestRepository $vybeDeletionRequestRepository;

    /**
     * @var VybeDeletionRequestService
     */
    protected VybeDeletionRequestService $vybeDeletionRequestService;

    /**
     * VybeDeletionRequestController constructor
     */
    public function __construct()
    {
        /** @var VybeDeletionRequestRepository vybeDeletionRequestRepository */
        $this->vybeDeletionRequestRepository = new VybeDeletionRequestRepository();

        /** @var VybeDeletionRequestService vybeDeletionRequestService */
        $this->vybeDeletionRequestService = new VybeDeletionRequestService();
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
         * Getting vybe deletion requests with pagination
         */
        $vybeDeletionRequests = $this->vybeDeletionRequestRepository->getAllFiltered(
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
         * Getting vybe deletion request statuses
         */
        $vybeDeletionRequestStatuses = $this->vybeDeletionRequestService->getAllStatusesWithCounts(
            $vybeDeletionRequests
        );

        /**
         * Checking pagination existence
         */
        if ($request->input('paginated') === true) {

            /**
             * Paginating vybe deletion requests
             */
            $paginatedVybeDeletionRequests = paginateCollection(
                $vybeDeletionRequests,
                $request->input('per_page') ?
                    $request->input('per_page') :
                    $this->vybeDeletionRequestRepository->getPerPage()
            );

            return $this->setPagination($paginatedVybeDeletionRequests)->respondWithSuccess(
                $this->transformItem([],
                    new VybeDeletionRequestListPageTransformer(
                        $vybeDeletionRequestStatuses,
                        new Collection($paginatedVybeDeletionRequests->items())
                    )
                )['vybe_deletion_request_list'],
                trans('validations/api/admin/request/vybe/deletionRequest/index.result.success')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem([],
                new VybeDeletionRequestListPageTransformer(
                    $vybeDeletionRequestStatuses,
                    $vybeDeletionRequests
                )
            )['vybe_deletion_request_list'],
            trans('validations/api/admin/request/vybe/deletionRequest/index.result.success')
        );
    }
}
