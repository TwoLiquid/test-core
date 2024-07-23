<?php

namespace App\Http\Controllers\Api\Admin\Request\User;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\Admin\Request\User\Interfaces\UserDeactivationRequestControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\Request\User\DeactivationRequest\IndexRequest;
use App\Repositories\User\UserDeactivationRequestRepository;
use App\Services\User\UserDeactivationRequestService;
use App\Transformers\Api\Admin\Request\User\DeactivationRequest\UserDeactivationRequestListPageTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class UserDeactivationRequestController
 *
 * @package App\Http\Controllers\Api\Admin\Request\User
 */
final class UserDeactivationRequestController extends BaseController implements UserDeactivationRequestControllerInterface
{
    /**
     * @var UserDeactivationRequestRepository
     */
    protected UserDeactivationRequestRepository $userDeactivationRequestRepository;

    /**
     * @var UserDeactivationRequestService
     */
    protected UserDeactivationRequestService $userDeactivationRequestService;

    /**
     * UserDeactivationRequestController constructor
     */
    public function __construct()
    {
        /** @var UserDeactivationRequestRepository userDeactivationRequestRepository */
        $this->userDeactivationRequestRepository = new UserDeactivationRequestRepository();

        /** @var UserDeactivationRequestService userDeactivationRequestService */
        $this->userDeactivationRequestService = new UserDeactivationRequestService();
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
         * Getting user deactivation requests with pagination
         */
        $userDeactivationRequests = $this->userDeactivationRequestRepository->getAllFiltered(
            $request->input('request_id'),
            $request->input('user_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('username'),
            $request->input('sales'),
            $request->input('languages_ids'),
            $request->input('user_statuses_ids'),
            $request->input('request_statuses_ids'),
            $request->input('admin'),
            $request->input('sort_by'),
            $request->input('sort_order')
        );

        /**
         * Getting user deactivation request statuses
         */
        $userDeactivationRequestStatuses = $this->userDeactivationRequestService->getAllStatusesWithCounts(
            $userDeactivationRequests
        );

        /**
         * Checking pagination existence
         */
        if ($request->input('paginated') === true) {

            /**
             * Paginating user deactivation requests
             */
            $paginatedUserDeactivationRequests = paginateCollection(
                $userDeactivationRequests,
                $request->input('per_page') ?
                    $request->input('per_page') :
                    $this->userDeactivationRequestRepository->getPerPage()
            );

            return $this->setPagination($paginatedUserDeactivationRequests)->respondWithSuccess(
                $this->transformItem([],
                    new UserDeactivationRequestListPageTransformer(
                        $userDeactivationRequestStatuses,
                        new Collection($paginatedUserDeactivationRequests->items())
                    )
                )['user_deactivation_request_list'],
                trans('validations/api/admin/request/user/deactivationRequest/index.result.success')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem([],
                new UserDeactivationRequestListPageTransformer(
                    $userDeactivationRequestStatuses,
                    $userDeactivationRequests
                )
            )['user_deactivation_request_list'],
            trans('validations/api/admin/request/user/deactivationRequest/index.result.success')
        );
    }
}
