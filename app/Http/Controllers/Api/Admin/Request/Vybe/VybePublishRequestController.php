<?php

namespace App\Http\Controllers\Api\Admin\Request\Vybe;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\Admin\Request\Vybe\Interfaces\VybePublishRequestControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\Request\Vybe\PublishRequest\IndexRequest;
use App\Repositories\Vybe\VybePublishRequestRepository;
use App\Services\Vybe\VybePublishRequestService;
use App\Transformers\Api\Admin\Request\Vybe\PublishRequest\VybePublishRequestListPageTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class VybePublishRequestController
 *
 * @package App\Http\Controllers\Api\Admin\Request\Vybe
 */
final class VybePublishRequestController extends BaseController implements VybePublishRequestControllerInterface
{
    /**
     * @var VybePublishRequestRepository
     */
    protected VybePublishRequestRepository $vybePublishRequestRepository;

    /**
     * @var VybePublishRequestService
     */
    protected VybePublishRequestService $vybePublishRequestService;

    /**
     * VybePublishRequestController constructor
     */
    public function __construct()
    {
        /** @var VybePublishRequestRepository vybePublishRequestRepository */
        $this->vybePublishRequestRepository = new VybePublishRequestRepository();

        /** @var VybePublishRequestService vybePublishRequestService */
        $this->vybePublishRequestService = new VybePublishRequestService();
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
         * Getting vybe publish requests with pagination
         */
        $vybePublishRequests = $this->vybePublishRequestRepository->getAllFiltered(
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
         * Getting vybe publish request statuses
         */
        $vybePublishRequestStatuses = $this->vybePublishRequestService->getAllStatusesWithCounts(
            $vybePublishRequests
        );

        /**
         * Checking pagination existence
         */
        if ($request->input('paginated') === true) {

            /**
             * Paginating vybe publish requests
             */
            $paginatedVybePublishRequests = paginateCollection(
                $vybePublishRequests,
                $request->input('per_page') ?
                    $request->input('per_page') :
                    $this->vybePublishRequestRepository->getPerPage()
            );

            return $this->setPagination($paginatedVybePublishRequests)->respondWithSuccess(
                $this->transformItem([],
                    new VybePublishRequestListPageTransformer(
                        $vybePublishRequestStatuses,
                        new Collection($paginatedVybePublishRequests->items())
                    )
                )['vybe_publish_request_list'],
                trans('validations/api/admin/request/vybe/publishRequest/index.result.success')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem([],
                new VybePublishRequestListPageTransformer(
                    $vybePublishRequestStatuses,
                    $vybePublishRequests
                )
            )['vybe_publish_request_list'],
            trans('validations/api/admin/request/vybe/publishRequest/index.result.success')
        );
    }
}
