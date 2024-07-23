<?php

namespace App\Http\Controllers\Api\General\Setting;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Exceptions\MicroserviceException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\General\Setting\Interfaces\UserIdVerificationControllerInterface;
use App\Http\Requests\Api\General\Setting\IdVerification\UpdateRequest;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\User\IdVerification\Status\UserIdVerificationStatusList;
use App\Microservices\Media\MediaMicroservice;
use App\Repositories\Media\UserIdVerificationImageRepository;
use App\Repositories\User\UserIdVerificationRequestRepository;
use App\Services\Admin\AdminNavbarService;
use App\Services\Auth\AuthService;
use App\Services\File\MediaService;
use App\Services\User\UserIdVerificationRequestService;
use App\Transformers\Api\General\Setting\IdVerification\UserIdVerificationRequestTransformer;
use App\Transformers\Api\General\Setting\IdVerification\UserIdVerificationTransformer;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class UserIdVerificationController
 *
 * @package App\Http\Controllers\Api\General\Setting
 */
class UserIdVerificationController extends BaseController implements UserIdVerificationControllerInterface
{
    /**
     * @var AdminNavbarService
     */
    protected AdminNavbarService $adminNavbarService;

    /**
     * @var MediaService
     */
    protected MediaService $mediaService;

    /**
     * @var MediaMicroservice
     */
    protected MediaMicroservice $mediaMicroservice;

    /**
     * @var UserIdVerificationRequestRepository
     */
    protected UserIdVerificationRequestRepository $userIdVerificationRequestRepository;

    /**
     * @var UserIdVerificationRequestService
     */
    protected UserIdVerificationRequestService $userIdVerificationService;

    /**
     * @var UserIdVerificationImageRepository
     */
    protected UserIdVerificationImageRepository $userIdVerificationImageRepository;

    /**
     * UserIdVerificationController constructor
     */
    public function __construct()
    {
        /** @var AdminNavbarService adminNavbarService */
        $this->adminNavbarService = new AdminNavbarService();

        /** @var MediaService mediaService */
        $this->mediaService = new MediaService();

        /** @var MediaMicroservice mediaMicroservice */
        $this->mediaMicroservice = new MediaMicroservice();

        /** @var UserIdVerificationRequestRepository userIdVerificationRequestRepository */
        $this->userIdVerificationRequestRepository = new UserIdVerificationRequestRepository();

        /** @var UserIdVerificationRequestService userIdVerificationService */
        $this->userIdVerificationService = new UserIdVerificationRequestService();

        /** @var UserIdVerificationImageRepository userIdVerificationImageRepository */
        $this->userIdVerificationImageRepository = new UserIdVerificationImageRepository();
    }

    /**
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function index() : JsonResponse
    {
        /**
         * Getting user id verification request
         */
        $userIdVerificationRequest = $this->userIdVerificationRequestRepository->findLastForUser(
            AuthService::user()
        );

        return $this->respondWithSuccess(
            $this->transformItem([],
                new UserIdVerificationTransformer(
                    AuthService::user(),
                    $userIdVerificationRequest,
                    $this->userIdVerificationImageRepository->getByRequests(
                        new Collection([$userIdVerificationRequest])
                    )
                )
            )['user_id_verification'],
            trans('validations/api/general/setting/userIdVerification/index.result.success')
        );
    }

    /**
     * @param UpdateRequest $request
     *
     * @return JsonResponse
     *
     * @throws BaseException
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function update(
        UpdateRequest $request
    ) : JsonResponse
    {
        /**
         * Checking is user id verification suspended
         */
        if (AuthService::user()->verification_suspended === true) {
            return $this->respondWithError(
                trans('validations/api/general/setting/userIdVerification/update.result.error.suspended')
            );
        }

        /**
         * Checking user id verification status
         */
        if (!AuthService::user()->getIdVerificationStatus()
            ->isVerified()
        ) {

            /**
             * Validation user id document
             */
            $this->mediaService->validateUserIdVerificationImage(
                $request->input('content'),
                $request->input('mime'),
            );

            /**
             * Getting pending user id verification request
             */
            $userIdVerificationRequest = $this->userIdVerificationRequestRepository->findPendingForUser(
                AuthService::user()
            );

            if ($userIdVerificationRequest) {

                /**
                 * Uploading user id verification document
                 */
                $this->mediaMicroservice->storeUserIdVerificationImage(
                    AuthService::user(),
                    $userIdVerificationRequest,
                    $request->input('content'),
                    $request->input('mime'),
                    $request->input('extension')
                );

                return $this->respondWithSuccess(
                    $this->transformItem($userIdVerificationRequest, new UserIdVerificationRequestTransformer(
                        $this->userIdVerificationImageRepository->getByRequests(
                            new Collection([$userIdVerificationRequest])
                        )
                    )), trans('validations/api/general/setting/userIdVerification/update.result.success.update')
                );
            } else {

                /**
                 * Creating user id verification request
                 */
                $userIdVerificationRequest = $this->userIdVerificationRequestRepository->store(
                    AuthService::user(),
                    UserIdVerificationStatusList::getVerified()
                );

                if (!$userIdVerificationRequest) {
                    return $this->respondWithError(
                        trans('validations/api/general/setting/userIdVerification/update.result.error.create')
                    );
                }

                /**
                 * Updating user id verification request
                 */
                $this->userIdVerificationRequestRepository->updateLanguage(
                    $userIdVerificationRequest,
                    AuthService::user()->getLanguage()
                );

                /**
                 * Uploading user id verification document
                 */
                $this->mediaMicroservice->storeUserIdVerificationImage(
                    AuthService::user(),
                    $userIdVerificationRequest,
                    $request->input('content'),
                    $request->input('mime'),
                    $request->input('extension')
                );

                return $this->respondWithSuccess(
                    $this->transformItem($userIdVerificationRequest, new UserIdVerificationRequestTransformer(
                        $this->userIdVerificationImageRepository->getByRequests(
                            new Collection([$userIdVerificationRequest])
                        )
                    )), trans('validations/api/general/setting/userIdVerification/update.result.success.create')
                );
            }
        }

        /**
         * Releasing id verification request counter-caches
         */
        $this->adminNavbarService->releaseIdVerificationRequestCache();

        return $this->respondWithError(
            trans('validations/api/general/setting/userIdVerification/update.result.error.verification')
        );
    }

    /**
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function close() : JsonResponse
    {
        /**
         * Getting user id verification request
         */
        $userIdVerificationRequest = $this->userIdVerificationRequestRepository->findLastForUser(
            AuthService::user()
        );

        /**
         * Checking user id verification request existence
         */
        if (!$userIdVerificationRequest) {
            return $this->respondWithError(
                trans('validations/api/general/setting/userIdVerification/close.result.error.find')
            );
        }

        /**
         * Checking user id verification request status
         */
        if (!$userIdVerificationRequest->getRequestStatus()->isDeclined()) {
            return $this->respondWithError(
                trans('validations/api/general/setting/userIdVerification/close.result.error.status')
            );
        }

        /**
         * Updating id verification request
         */
        $this->userIdVerificationRequestRepository->updateShown(
            $userIdVerificationRequest,
            true
        );

        return $this->respondWithSuccess([],
            trans('validations/api/general/setting/userIdVerification/close.result.success')
        );
    }

    /**
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function destroy() : JsonResponse
    {
        /**
         * Getting pending id verification
         */
        $userIdVerificationRequest = $this->userIdVerificationRequestRepository->findPendingForUser(
            AuthService::user()
        );

        /**
         * Checking user id verification request
         */
        if (!$userIdVerificationRequest) {
            return $this->respondWithError(
                trans('validations/api/general/setting/userIdVerification/destroy.result.error.find')
            );
        }

        /**
         * Updating user id verification request status
         */
        $this->userIdVerificationRequestRepository->updateRequestStatus(
            $userIdVerificationRequest,
            RequestStatusList::getCanceledItem()
        );

        /**
         * Releasing user id verification request counter-caches
         */
        $this->adminNavbarService->releaseIdVerificationRequestCache();

        return $this->respondWithSuccess([],
            trans('validations/api/general/setting/userIdVerification/destroy.result.success')
        );
    }
}
