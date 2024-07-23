<?php

namespace App\Http\Controllers\Api\Admin\Request\User;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\Admin\Request\User\Interfaces\UserProfileRequestControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\Request\User\ProfileRequest\IndexRequest;
use App\Repositories\User\UserProfileRequestRepository;
use App\Services\User\UserProfileRequestService;
use App\Transformers\Api\Admin\Request\User\ProfileRequest\UserProfileRequestListPageTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class UserProfileRequestController
 *
 * @package App\Http\Controllers\Api\Admin\Request\User
 */
final class UserProfileRequestController extends BaseController implements UserProfileRequestControllerInterface
{
    /**
     * @var UserProfileRequestRepository
     */
    protected UserProfileRequestRepository $userProfileRequestRepository;

    /**
     * @var UserProfileRequestService
     */
    protected UserProfileRequestService $userProfileRequestService;

    /**
     * UserProfileRequestController constructor
     */
    public function __construct()
    {
        /** @var UserProfileRequestRepository userProfileRequestRepository */
        $this->userProfileRequestRepository = new UserProfileRequestRepository();

        /** @var UserProfileRequestService userProfileRequestService */
        $this->userProfileRequestService = new UserProfileRequestService();
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
         * Getting user profile requests with pagination
         */
        $userProfileRequests = $this->userProfileRequestRepository->getAllFiltered(
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
         * Getting user profile request statuses
         */
        $userProfileRequestStatuses = $this->userProfileRequestService->getAllStatusesWithCounts(
            $userProfileRequests
        );

        /**
         * Checking pagination existence
         */
        if ($request->input('paginated') === true) {

            /**
             * Paginating user profile requests
             */
            $paginatedUserProfileRequests = paginateCollection(
                $userProfileRequests,
                $request->input('per_page') ?
                    $request->input('per_page') :
                    $this->userProfileRequestRepository->getPerPage()
            );

            return $this->setPagination($paginatedUserProfileRequests)->respondWithSuccess(
                $this->transformItem([],
                    new UserProfileRequestListPageTransformer(
                        $userProfileRequestStatuses,
                        new Collection($paginatedUserProfileRequests->items())
                    )
                )['user_profile_request_list'],
                trans('validations/api/admin/request/user/profileRequest/index.result.success')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem([],
                new UserProfileRequestListPageTransformer(
                    $userProfileRequestStatuses,
                    $userProfileRequests
                )
            )['user_profile_request_list'],
            trans('validations/api/admin/request/user/profileRequest/index.result.success')
        );
    }
}
