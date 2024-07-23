<?php

namespace App\Http\Controllers\Api\Admin\Request\Vybe;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\Admin\Request\Vybe\Interfaces\VybeUnpublishRequestControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\Request\Vybe\UnpublishRequest\IndexRequest;
use App\Repositories\Vybe\VybeUnpublishRequestRepository;
use App\Services\Vybe\VybeUnpublishRequestService;
use App\Transformers\Api\Admin\Request\Vybe\UnpublishRequest\VybeUnpublishRequestListPageTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class VybeUnpublishRequestController
 *
 * @package App\Http\Controllers\Api\Admin\Request\Vybe
 */
final class VybeUnpublishRequestController extends BaseController implements VybeUnpublishRequestControllerInterface
{
    /**
     * @var VybeUnpublishRequestRepository
     */
    protected VybeUnpublishRequestRepository $vybeUnpublishRequestRepository;

    /**
     * @var VybeUnpublishRequestService
     */
    protected VybeUnpublishRequestService $vybeUnpublishRequestService;

    /**
     * VybeUnpublishRequestController constructor
     */
    public function __construct()
    {
        /** @var VybeUnpublishRequestRepository vybeUnpublishRequestRepository */
        $this->vybeUnpublishRequestRepository = new VybeUnpublishRequestRepository();

        /** @var VybeUnpublishRequestService vybeUnpublishRequestService */
        $this->vybeUnpublishRequestService = new VybeUnpublishRequestService();
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
         * Getting vybe unpublish requests with pagination
         */
        $vybeUnpublishRequests = $this->vybeUnpublishRequestRepository->getAllFiltered(
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
         * Getting vybe unpublish request statuses
         */
        $vybeUnpublishRequestStatuses = $this->vybeUnpublishRequestService->getAllStatusesWithCounts(
            $vybeUnpublishRequests
        );

        /**
         * Checking pagination existence
         */
        if ($request->input('paginated') === true) {

            /**
             * Paginating vybe unpublish requests
             */
            $paginatedVybeUnpublishRequests = paginateCollection(
                $vybeUnpublishRequests,
                $request->input('per_page') ?
                    $request->input('per_page') :
                    $this->vybeUnpublishRequestRepository->getPerPage()
            );

            return $this->setPagination($paginatedVybeUnpublishRequests)->respondWithSuccess(
                $this->transformItem([],
                    new VybeUnpublishRequestListPageTransformer(
                        $vybeUnpublishRequestStatuses,
                        new Collection($paginatedVybeUnpublishRequests->items())
                    )
                )['vybe_unpublish_request_list'],
                trans('validations/api/admin/request/vybe/unpublishRequest/index.result.success')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem([],
                new VybeUnpublishRequestListPageTransformer(
                    $vybeUnpublishRequestStatuses,
                    $vybeUnpublishRequests
                )
            )['vybe_unpublish_request_list'],
            trans('validations/api/admin/request/vybe/unpublishRequest/index.result.success')
        );
    }
}
