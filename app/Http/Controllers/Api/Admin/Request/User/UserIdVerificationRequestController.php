<?php

namespace App\Http\Controllers\Api\Admin\Request\User;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\Admin\Request\User\Interfaces\UserIdVerificationRequestControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\Request\User\IdVerificationRequest\IndexRequest;
use App\Repositories\User\UserIdVerificationRequestRepository;
use App\Services\User\UserIdVerificationRequestService;
use App\Transformers\Api\Admin\Request\User\IdVerificationRequest\UserIdVerificationRequestListPageTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class UserIdVerificationRequestController
 *
 * @package App\Http\Controllers\Api\Admin\Request\User
 */
final class UserIdVerificationRequestController extends BaseController implements UserIdVerificationRequestControllerInterface
{
    /**
     * @var UserIdVerificationRequestRepository
     */
    protected UserIdVerificationRequestRepository $userIdVerificationRequestRepository;

    /**
     * @var UserIdVerificationRequestService
     */
    protected UserIdVerificationRequestService $userIdVerificationRequestService;

    /**
     * UserIdVerificationRequestController constructor
     */
    public function __construct()
    {
        /** @var UserIdVerificationRequestRepository userIdVerificationRequestRepository */
        $this->userIdVerificationRequestRepository = new UserIdVerificationRequestRepository();

        /** @var UserIdVerificationRequestService userIdVerificationRequestService */
        $this->userIdVerificationRequestService = new UserIdVerificationRequestService();
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
         * Getting user id verification requests with pagination
         */
        $userIdVerificationRequests = $this->userIdVerificationRequestRepository->getAllFiltered(
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
         * Getting user id verification request statuses
         */
        $userIdVerificationStatuses = $this->userIdVerificationRequestService->getAllStatusesWithCounts(
            $userIdVerificationRequests
        );

        /**
         * Checking pagination existence
         */
        if ($request->input('paginated') === true) {

            /**
             * Paginating user id verification requests
             */
            $paginatedUserIdVerificationRequests = paginateCollection(
                $userIdVerificationRequests,
                $request->input('per_page') ?
                    $request->input('per_page') :
                    $this->userIdVerificationRequestRepository->getPerPage()
            );

            return $this->setPagination($paginatedUserIdVerificationRequests)->respondWithSuccess(
                $this->transformItem([],
                    new UserIdVerificationRequestListPageTransformer(
                        $userIdVerificationStatuses,
                        new Collection($paginatedUserIdVerificationRequests->items())
                    )
                )['user_id_verification_request_list'],
                trans('validations/api/admin/request/user/idVerificationRequest/index.result.success')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem([],
                new UserIdVerificationRequestListPageTransformer(
                    $userIdVerificationStatuses,
                    $userIdVerificationRequests
                )
            )['user_id_verification_request_list'],
            trans('validations/api/admin/request/user/idVerificationRequest/index.result.success')
        );
    }
}
