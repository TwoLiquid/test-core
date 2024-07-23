<?php

namespace App\Http\Controllers\Api\Admin\User\IdVerification;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\Admin\User\IdVerification\Interfaces\UserIdVerificationControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\User\IdVerification\UpdateRequest;
use App\Lists\Request\Field\Status\RequestFieldStatusList;
use App\Lists\User\IdVerification\Status\UserIdVerificationStatusList;
use App\Repositories\Media\UserIdVerificationImageRepository;
use App\Repositories\User\UserIdVerificationRequestRepository;
use App\Repositories\User\UserRepository;
use App\Services\User\UserIdVerificationRequestService;
use App\Transformers\Api\Admin\User\IdVerification\UserIdVerificationRequestTransformer;
use App\Transformers\Api\Admin\User\IdVerification\UserIdVerificationStatusTransformer;
use App\Transformers\Api\Admin\User\IdVerification\RequestFieldStatusTransformer;
use App\Transformers\Api\Admin\User\IdVerification\UserTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class UserIdVerificationController
 *
 * @package App\Http\Controllers\Api\Admin\User\IdVerification
 */
final class UserIdVerificationController extends BaseController implements UserIdVerificationControllerInterface
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
     * @var UserIdVerificationImageRepository
     */
    protected UserIdVerificationImageRepository $userIdVerificationImageRepository;

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * UserIdVerificationController constructor
     */
    public function __construct()
    {
        /** @var UserIdVerificationRequestRepository userIdVerificationRequestRepository */
        $this->userIdVerificationRequestRepository = new UserIdVerificationRequestRepository();

        /** @var UserIdVerificationRequestService userIdVerificationRequestService */
        $this->userIdVerificationRequestService = new UserIdVerificationRequestService();

        /** @var UserIdVerificationImageRepository userIdVerificationImageRepository */
        $this->userIdVerificationImageRepository = new UserIdVerificationImageRepository();

        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function index(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting user
         */
        $user = $this->userRepository->findByIdForAdmin($id);

        /**
         * Checking user existence
         */
        if (!$user) {
            return $this->respondWithError(
                trans('validations/api/admin/user/idVerification/index.result.error.find')
            );
        }

        /**
         * Getting last user id verification request
         */
        $lastIdVerificationRequest = $this->userIdVerificationRequestService->getLastForUser(
            $user
        );

        /**
         * Getting user id verification request history
         */
        $idVerificationRequestHistory = $this->userIdVerificationRequestRepository->getForUser(
            $user
        );

        /**
         * Getting verified image if exists
         */
        $image = null;

        if ($user->getIdVerificationStatus() &&
            $user->getIdVerificationStatus()->isVerified()
        ) {
            if (isset($idVerificationRequestHistory[0])) {
                $image = $this->userIdVerificationImageRepository->getByRequest(
                    $idVerificationRequestHistory[0]
                )->first()->toArray();
            }
        }

        return $this->respondWithSuccess([
            'user'                    => $this->transformItem($user, new UserTransformer)['user'],
            'user_id_verification_request' => $this->transformItem(
                $lastIdVerificationRequest,
                new UserIdVerificationRequestTransformer(
                    $this->userIdVerificationImageRepository->getByRequests(
                        new Collection([$lastIdVerificationRequest])
                    )
                )
            )['user_id_verification_request'],
            'request_history'         => $this->transformCollection(
                $idVerificationRequestHistory,
                new UserIdVerificationRequestTransformer(
                    $this->userIdVerificationImageRepository->getByRequests(
                        $idVerificationRequestHistory
                    )
                )
            )['user_id_verification_requests'],
            'image' => $image,
            'form'  => [
                'request_field_statuses'   => $this->transformCollection(
                    RequestFieldStatusList::getItems(),
                    new RequestFieldStatusTransformer
                )['request_field_statuses'],
                'user_id_verification_statuses' => $this->transformCollection(
                    UserIdVerificationStatusList::getItems(),
                    new UserIdVerificationStatusTransformer
                )['user_id_verification_statuses'],
            ]
        ], trans('validations/api/admin/user/idVerification/index.result.success'));
    }

    /**
     * @param int $id
     * @param UpdateRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function update(
        int $id,
        UpdateRequest $request
    ) : JsonResponse
    {
        /**
         * Getting user
         */
        $user = $this->userRepository->findById($id);

        /**
         * Checking user existence
         */
        if (!$user) {
            return $this->respondWithError(
                trans('validations/api/admin/user/idVerification/update.result.error.find')
            );
        }

        /**
         * Getting verified image if exists
         */
        $image = null;

        if ($user->getIdVerificationStatus() &&
            $user->getIdVerificationStatus()->isVerified()
        ) {

            /**
             * Getting accepted user id verification request
             */
            $acceptedIdVerificationRequest = $this->userIdVerificationRequestRepository->findAcceptedForUser(
                $user
            );

            /**
             * Getting id verification request media data
             */
            $acceptedIdVerificationRequestData = $this->transformItem(
                $acceptedIdVerificationRequest,
                new UserIdVerificationRequestTransformer(
                    $this->userIdVerificationImageRepository->getByRequests(
                        new Collection([$acceptedIdVerificationRequest])
                    )
                )
            )['user_id_verification_request'];

            if (isset($acceptedIdVerificationRequestData['document'])) {
                $image = $acceptedIdVerificationRequestData['document'];
            }
        }

        /**
         * Getting verification data
         */
        $idVerificationStatusListItem = UserIdVerificationStatusList::getItem(
            $request->input('verification_status_id')
        );

        /**
         * Update user verification
         */
        $this->userRepository->updateVerification(
            $user,
            $idVerificationStatusListItem,
            $request->input('verification_suspended')
        );

        /**
         * Getting user id verification request history
         */
        $idVerificationRequestHistory = $this->userIdVerificationRequestRepository->getForUser(
            $user
        );

        return $this->respondWithSuccess([
                'user'            => $this->transformItem($user, new UserTransformer)['user'],
                'image'           => $image,
                'request_history' => $this->transformCollection(
                    $idVerificationRequestHistory,
                    new UserIdVerificationRequestTransformer(
                        $this->userIdVerificationImageRepository->getByRequests(
                            $idVerificationRequestHistory
                        )
                    )
                )['user_id_verification_requests'],
            ], trans('validations/api/admin/user/idVerification/update.result.success')
        );
    }
}
