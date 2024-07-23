<?php

namespace App\Http\Controllers\Api\Admin\Request\User;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\Admin\Request\User\Interfaces\UserDeletionRequestControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\Request\User\DeletionRequest\IndexRequest;
use App\Repositories\User\UserDeletionRequestRepository;
use App\Services\User\UserDeletionRequestService;
use App\Transformers\Api\Admin\Request\User\DeletionRequest\UserDeletionRequestListPageTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class UserDeletionRequestController
 *
 * @package App\Http\Controllers\Api\Admin\Request\User
 */
final class UserDeletionRequestController extends BaseController implements UserDeletionRequestControllerInterface
{
    /**
     * @var UserDeletionRequestRepository
     */
    protected UserDeletionRequestRepository $userDeletionRequestRepository;

    /**
     * @var UserDeletionRequestService
     */
    protected UserDeletionRequestService $userDeletionRequestService;

    /**
     * UserDeletionRequestController constructor
     */
    public function __construct()
    {
        /** @var UserDeletionRequestRepository userDeletionRequestRepository */
        $this->userDeletionRequestRepository = new UserDeletionRequestRepository();

        /** @var UserDeletionRequestService userDeletionRequestService */
        $this->userDeletionRequestService = new UserDeletionRequestService();
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
         * Getting user deletion requests with pagination
         */
        $userDeletionRequests = $this->userDeletionRequestRepository->getAllFiltered(
            $request->input('request_id'),
            $request->input('user_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('username'),
            $request->input('languages_ids'),
            $request->input('sales'),
            $request->input('user_statuses_ids'),
            $request->input('request_statuses_ids'),
            $request->input('admin'),
            $request->input('sort_by'),
            $request->input('sort_order')
        );

        /**
         * Getting user deletion request statuses
         */
        $userDeletionRequestStatuses = $this->userDeletionRequestService->getAllStatusesWithCounts(
            $userDeletionRequests
        );

        /**
         * Checking pagination existence
         */
        if ($request->input('paginated') === true) {

            /**
             * Paginating user deletion requests
             */
            $paginatedUserDeletionRequests = paginateCollection(
                $userDeletionRequests,
                $request->input('per_page') ?
                    $request->input('per_page') :
                    $this->userDeletionRequestRepository->getPerPage()
            );

            return $this->setPagination($paginatedUserDeletionRequests)->respondWithSuccess(
                $this->transformItem([],
                    new UserDeletionRequestListPageTransformer(
                        $userDeletionRequestStatuses,
                        new Collection($paginatedUserDeletionRequests->items())
                    )
                )['user_deletion_request_list'],
                trans('validations/api/admin/request/user/deletionRequest/index.result.success')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem([],
                new UserDeletionRequestListPageTransformer(
                    $userDeletionRequestStatuses,
                    $userDeletionRequests
                )
            )['user_deletion_request_list'],
            trans('validations/api/admin/request/user/deletionRequest/index.result.success')
        );
    }
}
