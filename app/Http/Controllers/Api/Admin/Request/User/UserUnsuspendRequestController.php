<?php

namespace App\Http\Controllers\Api\Admin\Request\User;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\Admin\Request\User\Interfaces\UserUnsuspendRequestControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\Request\User\UnsuspendRequest\IndexRequest;
use App\Repositories\User\UserUnsuspendRequestRepository;
use App\Services\User\UserUnsuspendRequestService;
use App\Transformers\Api\Admin\Request\User\UnsuspendRequest\UserUnsuspendRequestListPageTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class UserUnsuspendRequestController
 *
 * @package App\Http\Controllers\Api\Admin\Request\User
 */
final class UserUnsuspendRequestController extends BaseController implements UserUnsuspendRequestControllerInterface
{
    /**
     * @var UserUnsuspendRequestRepository
     */
    protected UserUnsuspendRequestRepository $userUnsuspendRequestRepository;

    /**
     * @var UserUnsuspendRequestService
     */
    protected UserUnsuspendRequestService $userUnsuspendRequestService;

    /**
     * UserUnsuspendRequestController constructor
     */
    public function __construct()
    {
        /** @var UserUnsuspendRequestRepository userUnsuspendRequestRepository */
        $this->userUnsuspendRequestRepository = new UserUnsuspendRequestRepository();

        /** @var UserUnsuspendRequestService userUnsuspendRequestService */
        $this->userUnsuspendRequestService = new UserUnsuspendRequestService();
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
         * Getting user unsuspend requests with pagination
         */
        $userUnsuspendRequests = $this->userUnsuspendRequestRepository->getAllFiltered(
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
         * Getting user unsuspend request statuses
         */
        $userUnsuspendRequestStatuses = $this->userUnsuspendRequestService->getAllStatusesWithCounts(
            $userUnsuspendRequests
        );

        /**
         * Checking pagination existence
         */
        if ($request->input('paginated') === true) {

            /**
             * Paginating user unsuspend requests
             */
            $paginatedUserUnsuspendRequests = paginateCollection(
                $userUnsuspendRequests,
                $request->input('per_page') ?
                    $request->input('per_page') :
                    $this->userUnsuspendRequestRepository->getPerPage()
            );

            return $this->setPagination($paginatedUserUnsuspendRequests)->respondWithSuccess(
                $this->transformItem([],
                    new UserUnsuspendRequestListPageTransformer(
                        $userUnsuspendRequestStatuses,
                        new Collection($paginatedUserUnsuspendRequests->items())
                    )
                )['user_unsuspend_request_list'],
                trans('validations/api/admin/request/user/unsuspendRequest/index.result.success')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem([],
                new UserUnsuspendRequestListPageTransformer(
                    $userUnsuspendRequestStatuses,
                    $userUnsuspendRequests
                )
            )['user_unsuspend_request_list'],
            trans('validations/api/admin/request/user/unsuspendRequest/index.result.success')
        );
    }
}
