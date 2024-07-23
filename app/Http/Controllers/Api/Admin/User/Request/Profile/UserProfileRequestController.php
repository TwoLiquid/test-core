<?php

namespace App\Http\Controllers\Api\Admin\User\Request\Profile;

use App\Exceptions\DatabaseException;
use App\Exceptions\MicroserviceException;
use App\Http\Controllers\Api\Admin\User\Request\Profile\Interfaces\UserProfileRequestControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\User\Request\Profile\UpdateRequest;
use App\Lists\Request\Field\Status\RequestFieldStatusList;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\ToastMessage\Type\ToastMessageTypeList;
use App\Microservices\Auth\AuthMicroservice;
use App\Microservices\Media\MediaMicroservice;
use App\Repositories\Media\UserAvatarRepository;
use App\Repositories\Media\UserBackgroundRepository;
use App\Repositories\Media\UserImageRepository;
use App\Repositories\Media\UserVideoRepository;
use App\Repositories\Media\UserVoiceSampleRepository;
use App\Repositories\User\UserProfileRequestRepository;
use App\Repositories\User\UserRepository;
use App\Services\Auth\AuthService;
use App\Services\File\MediaService;
use App\Services\Notification\EmailNotificationService;
use App\Services\User\UserProfileRequestService;
use App\Services\User\UserService;
use App\Transformers\Api\Admin\User\Request\Profile\UserProfileRequestTransformer;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class UserProfileRequestController
 *
 * @package App\Http\Controllers\Api\Admin\User\Request\Profile
 */
final class UserProfileRequestController extends BaseController implements UserProfileRequestControllerInterface
{
    /**
     * @var AuthMicroservice
     */
    protected AuthMicroservice $authMicroservice;

    /**
     * @var EmailNotificationService
     */
    protected EmailNotificationService $emailNotificationService;

    /**
     * @var MediaMicroservice
     */
    protected MediaMicroservice $mediaMicroservice;

    /**
     * @var MediaService
     */
    protected MediaService $mediaService;

    /**
     * @var UserProfileRequestRepository
     */
    protected UserProfileRequestRepository $userProfileRequestRepository;

    /**
     * @var UserProfileRequestService
     */
    protected UserProfileRequestService $userProfileRequestService;

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * @var UserService
     */
    protected UserService $userService;

    /**
     * @var UserAvatarRepository
     */
    protected UserAvatarRepository $userAvatarRepository;

    /**
     * @var UserBackgroundRepository
     */
    protected UserBackgroundRepository $userBackgroundRepository;

    /**
     * @var UserVoiceSampleRepository
     */
    protected UserVoiceSampleRepository $userVoiceSampleRepository;

    /**
     * @var UserImageRepository
     */
    protected UserImageRepository $userImageRepository;

    /**
     * @var UserVideoRepository
     */
    protected UserVideoRepository $userVideoRepository;

    /**
     * UserProfileRequestController constructor
     */
    public function __construct()
    {
        /** @var AuthMicroservice authMicroservice */
        $this->authMicroservice = new AuthMicroservice();

        /** @var EmailNotificationService emailNotificationService */
        $this->emailNotificationService = new EmailNotificationService();

        /** @var MediaMicroservice mediaMicroservice */
        $this->mediaMicroservice = new MediaMicroservice();

        /** @var MediaService mediaService */
        $this->mediaService = new MediaService();

        /** @var UserProfileRequestRepository userProfileRequestRepository */
        $this->userProfileRequestRepository = new UserProfileRequestRepository();

        /** @var UserProfileRequestService userProfileRequestService */
        $this->userProfileRequestService = new UserProfileRequestService();

        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();

        /** @var UserService userService */
        $this->userService = new UserService();

        /** @var UserVideoRepository userAvatarRepository */
        $this->userAvatarRepository = new UserAvatarRepository();

        /** @var UserBackgroundRepository userBackgroundRepository */
        $this->userBackgroundRepository = new UserBackgroundRepository();

        /** @var UserVoiceSampleRepository userVoiceSampleRepository */
        $this->userVoiceSampleRepository = new UserVoiceSampleRepository();

        /** @var UserImageRepository userImageRepository */
        $this->userImageRepository = new UserImageRepository();

        /** @var UserVideoRepository userVideoRepository */
        $this->userVideoRepository = new UserVideoRepository();
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
        $user = $this->userRepository->findById($id);

        /**
         * Checking user existence
         */
        if (!$user) {
            return $this->respondWithError(
                trans('validations/api/admin/user/request/profile/show.result.error.find.user')
            );
        }

        /**
         * Getting pending user profile request
         */
        $userProfileRequest = $this->userProfileRequestRepository->findPendingForUser(
            $user
        );

        /**
         * Checking user profile request existence
         */
        if (!$userProfileRequest) {
            return $this->respondWithError(
                trans('validations/api/admin/user/request/profile/show.result.error.find.profileRequest')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($userProfileRequest, new UserProfileRequestTransformer(
                $this->userAvatarRepository->getByUsers(
                    $this->userService->getByFullProfile(
                        new Collection([$user])
                    )
                ),
                $this->userBackgroundRepository->getByUsers(
                    new Collection([$user])
                ),
                $this->userVoiceSampleRepository->getByUsers(
                    $this->userService->getByFullProfile(
                        new Collection([$user])
                    )
                ),
                $this->userImageRepository->getByUsers(
                    new Collection([$user])
                ),
                $this->userVideoRepository->getByUsers(
                    new Collection([$user])
                ),
                $this->userAvatarRepository->getByRequests(
                    new Collection([$userProfileRequest])
                ),
                $this->userBackgroundRepository->getByRequests(
                    new Collection([$userProfileRequest])
                ),
                $this->userVoiceSampleRepository->getByRequests(
                    new Collection([$userProfileRequest])
                ),
                $this->userImageRepository->getByRequests(
                    new Collection([$userProfileRequest])
                ),
                $this->userVideoRepository->getByRequests(
                    new Collection([$userProfileRequest])
                )
            )),
            trans('validations/api/admin/user/request/profile/show.result.success')
        );
    }

    /**
     * @param int $id
     * @param UpdateRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws MicroserviceException
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
                trans('validations/api/admin/user/request/profile/update.result.error.find.user')
            );
        }

        /**
         * Getting pending user profile request
         */
        $userProfileRequest = $this->userProfileRequestRepository->findPendingForUser(
            $user
        );

        /**
         * Checking user profile request existence
         */
        if (!$userProfileRequest) {
            return $this->respondWithError(
                trans('validations/api/admin/user/request/profile/update.result.error.find.profileRequest')
            );
        }

        /**
         * Getting account status
         */
        $accountStatusStatus = null;

        if ($userProfileRequest->getAccountStatusStatus()) {
            $accountStatusStatus = RequestFieldStatusList::getItem(
                $request->input('account_status_status_id')
            );
        }

        /**
         * Getting username status
         */
        $usernameStatus = null;

        if ($userProfileRequest->getUsernameStatus()) {
            $usernameStatus = RequestFieldStatusList::getItem(
                $request->input('username_status_id')
            );
        }

        /**
         * Getting birthdate status
         */
        $birthdateStatus = null;

        if ($userProfileRequest->getBirthdateStatus()) {
            $birthdateStatus = RequestFieldStatusList::getItem(
                $request->input('birth_date_status_id')
            );
        }

        /**
         * Getting description status
         */
        $descriptionStatus = null;

        if ($userProfileRequest->getDescriptionStatus()) {
            $descriptionStatus = RequestFieldStatusList::getItem(
                $request->input('description_status_id')
            );
        }

        /**
         * Getting voice sample status
         */
        $voiceSampleStatus = null;

        if ($userProfileRequest->getVoiceSampleStatus()) {
            $voiceSampleStatus = RequestFieldStatusList::getItem(
                $request->input('voice_sample_status_id')
            );
        }

        /**
         * Getting avatar status
         */
        $avatarStatus = null;

        if ($userProfileRequest->getAvatarStatus()) {
            $avatarStatus = RequestFieldStatusList::getItem(
                $request->input('avatar_status_id')
            );
        }

        /**
         * Getting background status
         */
        $backgroundStatus = null;

        if ($userProfileRequest->getBackgroundStatus()) {
            $backgroundStatus = RequestFieldStatusList::getItem(
                $request->input('background_status_id')
            );
        }

        /**
         * Getting album status
         */
        $albumStatus = null;

        if ($userProfileRequest->getAlbumStatus()) {
            $albumStatus = RequestFieldStatusList::getItem(
                $request->input('album_status_id')
            );
        }

        /**
         * Check user profile request parameters statuses
         */
        if (!$this->userProfileRequestService->isParametersProcessed(
            $userProfileRequest,
            $accountStatusStatus,
            $usernameStatus,
            $birthdateStatus,
            $descriptionStatus,
            $voiceSampleStatus,
            $avatarStatus,
            $backgroundStatus,
            $albumStatus
        )) {
            return $this->respondWithError(
                trans('validations/api/admin/user/request/profile/update.result.error.unprocessed.common')
            );
        }

        /**
         * Checking is user account status pending
         */
        if ($user->getAccountStatus()->isPending()) {

            /**
             * Check user profile request parameters statuses for pending user
             */
            if (!$this->userProfileRequestService->isProcessedForPendingUser(
                $accountStatusStatus,
                $usernameStatus,
                $birthdateStatus,
                $descriptionStatus,
                $voiceSampleStatus,
                $avatarStatus,
                $backgroundStatus,
                $albumStatus
            )) {
                return $this->respondWithError(
                    trans('validations/api/admin/user/request/profile/update.result.error.unprocessed.user')
                );
            }
        }

        /**
         * Getting user account status
         */
        $accountStatus = null;

        if ($userProfileRequest->getAccountStatusStatus()) {
            if ($accountStatusStatus->isAccepted()) {
                $accountStatus = $userProfileRequest->getAccountStatus();
            }
        }

        /**
         * Getting user username
         */
        $username = $userProfileRequest->username;

        if ($userProfileRequest->getUsernameStatus()) {
            if ($usernameStatus->isDeclined()) {
                $username = generateUsername();
            }
        }

        /**
         * Getting user birthdate
         */
        $birthdate = null;

        if ($userProfileRequest->getBirthdateStatus()) {
            if ($birthdateStatus->isAccepted()) {
                $birthdate = $userProfileRequest->birth_date->format('Y-m-d H:i:s');
            }
        }

        /**
         * Getting user description
         */
        $description = null;

        if ($userProfileRequest->getDescriptionStatus()) {
            if ($descriptionStatus->isAccepted()) {
                $description = $userProfileRequest->description;
            }
        }

        /**
         * Updating user profile request
         */
        $userProfileRequest = $this->userProfileRequestRepository->update(
            $userProfileRequest,
            $accountStatusStatus,
            $usernameStatus,
            $birthdateStatus,
            $descriptionStatus,
            $voiceSampleStatus,
            $avatarStatus,
            $backgroundStatus,
            $albumStatus,
            $request->input('toast_message_text')
        );

        /**
         * Updating user
         */
        $user = $this->userRepository->updateForProfileRequest(
            $user,
            $accountStatus,
            $username,
            $birthdate,
            $description
        );

        /**
         * Checking voice sample status
         */
        if ($userProfileRequest->getVoiceSampleStatus() && $voiceSampleStatus) {

            try {

                /**
                 * Check voice sample acceptance
                 */
                if ($voiceSampleStatus->isAccepted()) {

                    /**
                     * Accepting voice sample
                     */
                    $this->mediaMicroservice->acceptUserVoiceSample(
                        $userProfileRequest
                    );
                } else {

                    /**
                     * Declining voice sample
                     */
                    $this->mediaMicroservice->declineUserVoiceSample(
                        $userProfileRequest
                    );
                }
            } catch (Exception $exception) {

                /**
                 * Adding background error to controller stack
                 */
                $this->addBackgroundError(
                    $exception
                );
            }
        }

        /**
         * Checking avatar status
         */
        if ($userProfileRequest->getAvatarStatus() && $avatarStatus) {

            try {

                /**
                 * Check avatar acceptance
                 */
                if ($avatarStatus->isAccepted()) {

                    /**
                     * Accepting avatar
                     */
                    $this->mediaMicroservice->acceptUserAvatar(
                        $userProfileRequest
                    );
                } else {

                    /**
                     * Declining avatar
                     */
                    $this->mediaMicroservice->declineUserAvatar(
                        $userProfileRequest
                    );
                }
            } catch (Exception $exception) {

                /**
                 * Adding background error to controller stack
                 */
                $this->addBackgroundError(
                    $exception
                );
            }
        }

        /**
         * Checking background status
         */
        if ($userProfileRequest->getBackgroundStatus() && $backgroundStatus) {

            try {

                /**
                 * Check background acceptance
                 */
                if ($backgroundStatus->isAccepted()) {

                    /**
                     * Accepting background
                     */
                    $this->mediaMicroservice->acceptUserBackground(
                        $userProfileRequest
                    );
                } else {

                    /**
                     * Declining background
                     */
                    $this->mediaMicroservice->declineUserBackground(
                        $userProfileRequest
                    );
                }
            } catch (Exception $exception) {

                /**
                 * Adding background error to controller stack
                 */
                $this->addBackgroundError(
                    $exception
                );
            }
        }

        /**
         * Checking album status
         */
        if ($userProfileRequest->getAlbumStatus() && $albumStatus) {

            /**
             * Check voice sample acceptance
             */
            if ($albumStatus->isAccepted()) {

                try {

                    /**
                     * Accepting images
                     */
                    $this->mediaMicroservice->acceptUserImages(
                        $userProfileRequest
                    );
                } catch (Exception $exception) {

                    /**
                     * Adding background error to controller stack
                     */
                    $this->addBackgroundError(
                        $exception
                    );
                }

                try {

                    /**
                     * Accepting videos
                     */
                    $this->mediaMicroservice->acceptUserVideos(
                        $userProfileRequest
                    );
                } catch (Exception $exception) {

                    /**
                     * Adding background error to controller stack
                     */
                    $this->addBackgroundError(
                        $exception
                    );
                }
            } else {

                try {

                    /**
                     * Declining images
                     */
                    $this->mediaMicroservice->declineUserImages(
                        $userProfileRequest
                    );
                } catch (Exception $exception) {

                    /**
                     * Adding background error to controller stack
                     */
                    $this->addBackgroundError(
                        $exception
                    );
                }

                try {

                    /**
                     * Declining videos
                     */
                    $this->mediaMicroservice->declineUserVideos(
                        $userProfileRequest
                    );
                } catch (Exception $exception) {

                    /**
                     * Adding background error to controller stack
                     */
                    $this->addBackgroundError(
                        $exception
                    );
                }
            }
        }

        /**
         * Check user profile request acceptance
         */
        if ($this->userProfileRequestService->isAccepted(
            $userProfileRequest
        )) {

            /**
             * Checking username existence
             */
            if ($username) {

                /**
                 * Updating gateway username
                 */
                $this->authMicroservice->updateUsername(
                    $user->email,
                    $username
                );
            }

            /**
             * Updating user profile request media
             */
            $this->userProfileRequestService->updateAcceptedMedia(
                $userProfileRequest,
                $avatarStatus,
                $backgroundStatus,
                $voiceSampleStatus,
                $albumStatus
            );

            /**
             * Updating user profile request status
             */
            $this->userProfileRequestRepository->updateRequestStatus(
                $userProfileRequest,
                RequestStatusList::getAcceptedItem()
            );

            /**
             * Updating a user profile request toast message type
             */
            $this->userProfileRequestRepository->updateToastMessageType(
                $userProfileRequest,
                ToastMessageTypeList::getAcceptedItem()
            );

            /**
             * Sending user profile request approve email notification
             */
            $this->emailNotificationService->sendUserProfileApproved(
                $user
            );

            /**
             * Check if user profile request declined
             */
        } elseif ($this->userProfileRequestService->isDeclined(
            $userProfileRequest
        )) {

            /**
             * Checking user account status
             */
            if ($user->getAccountStatus()->isActive()) {

                /**
                 * Updating user profile request media
                 */
                $this->userProfileRequestService->updateAcceptedMedia(
                    $userProfileRequest,
                    $avatarStatus,
                    $backgroundStatus,
                    $voiceSampleStatus,
                    $albumStatus
                );
            }

            /**
             * Updating user profile request status
             */
            $this->userProfileRequestRepository->updateRequestStatus(
                $userProfileRequest,
                RequestStatusList::getDeclinedItem()
            );

            /**
             * Updating a user profile request toast message type
             */
            $this->userProfileRequestRepository->updateToastMessageType(
                $userProfileRequest,
                ToastMessageTypeList::getDeclinedItem()
            );

            /**
             * Sending user profile request decline email notification
             */
            $this->emailNotificationService->sendUserProfileDeclined(
                $user
            );
        }

        /**
         * Updating processing admin
         */
        $this->userProfileRequestRepository->updateAdmin(
            $userProfileRequest,
            AuthService::admin()
        );

        return $this->respondWithSuccess(
            $this->transformItem($userProfileRequest, new UserProfileRequestTransformer(
                $this->userAvatarRepository->getByUsers(
                    $this->userService->getByFullProfile(
                        new Collection([$user])
                    )
                ),
                $this->userBackgroundRepository->getByUsers(
                    new Collection([$user])
                ),
                $this->userVoiceSampleRepository->getByUsers(
                    $this->userService->getByFullProfile(
                        new Collection([$user])
                    )
                ),
                $this->userImageRepository->getByUsers(
                    new Collection([$user])
                ),
                $this->userVideoRepository->getByUsers(
                    new Collection([$user])
                ),
                $this->userAvatarRepository->getByRequests(
                    new Collection([$userProfileRequest])
                ),
                $this->userBackgroundRepository->getByRequests(
                    new Collection([$userProfileRequest])
                ),
                $this->userVoiceSampleRepository->getByRequests(
                    new Collection([$userProfileRequest])
                ),
                $this->userImageRepository->getByRequests(
                    new Collection([$userProfileRequest])
                ),
                $this->userVideoRepository->getByRequests(
                    new Collection([$userProfileRequest])
                )
            )), trans('validations/api/admin/user/request/profile/update.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function acceptAll(
        int $id
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
                trans('validations/api/admin/user/request/profile/acceptAll.result.error.find.user')
            );
        }

        /**
         * Getting pending user profile request
         */
        $userProfileRequest = $this->userProfileRequestRepository->findPendingForUser(
            $user
        );

        /**
         * Checking user profile request existence
         */
        if (!$userProfileRequest) {
            return $this->respondWithError(
                trans('validations/api/admin/user/request/profile/acceptAll.result.error.find.profileRequest')
            );
        }

        /**
         * Accepting user profile request
         */
        $userProfileRequest = $this->userProfileRequestService->acceptAll(
            $userProfileRequest
        );

        /**
         * Updating user
         */
        $this->userRepository->updateForProfileRequest(
            $user,
            $userProfileRequest->getAccountStatus(),
            $userProfileRequest->username,
            $userProfileRequest->birth_date,
            $userProfileRequest->description
        );

        /**
         * Updating user profiler request media
         */
        $this->userProfileRequestService->updateAcceptedMedia(
            $userProfileRequest,
            $userProfileRequest->getAvatarStatus(),
            $userProfileRequest->getBackgroundStatus(),
            $userProfileRequest->getVoiceSampleStatus(),
            $userProfileRequest->getAlbumStatus()
        );

        /**
         * Accepting user voice sample
         */
        $this->mediaMicroservice->acceptUserVoiceSample(
            $userProfileRequest
        );

        /**
         * Accepting user avatar
         */
        $this->mediaMicroservice->acceptUserAvatar(
            $userProfileRequest
        );

        /**
         * Accepting a user background
         */
        $this->mediaMicroservice->acceptUserBackground(
            $userProfileRequest
        );

        /**
         * Accepting user images
         */
        $this->mediaMicroservice->acceptUserImages(
            $userProfileRequest
        );

        /**
         * Accepting user voice sample
         */
        $this->mediaMicroservice->acceptUserVideos(
            $userProfileRequest
        );

        /**
         * Updating user profile request status
         */
        $this->userProfileRequestRepository->updateRequestStatus(
            $userProfileRequest,
            RequestStatusList::getAcceptedItem()
        );

        /**
         * Updating processing admin
         */
        $this->userProfileRequestRepository->updateAdmin(
            $userProfileRequest,
            AuthService::admin()
        );

        /**
         * Sending user profile request approve email notification
         */
        $this->emailNotificationService->sendUserProfileApproved(
            $user
        );

        return $this->respondWithSuccess(
            $this->transformItem($userProfileRequest, new UserProfileRequestTransformer),
            trans('validations/api/admin/user/request/profile/acceptAll.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function declineAll(
        int $id
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
                trans('validations/api/admin/user/request/profile/declineAll.result.error.find.user')
            );
        }

        /**
         * Getting pending user profile request
         */
        $userProfileRequest = $this->userProfileRequestRepository->findPendingForUser(
            $user
        );

        /**
         * Checking user profile request existence
         */
        if (!$userProfileRequest) {
            return $this->respondWithError(
                trans('validations/api/admin/user/request/profile/declineAll.result.error.find.profileRequest')
            );
        }

        /**
         * Accepting user profile request
         */
        $userProfileRequest = $this->userProfileRequestService->declineAll(
            $userProfileRequest
        );

        /**
         * Declining user avatar
         */
        $this->mediaMicroservice->declineUserAvatar(
            $userProfileRequest
        );

        /**
         * Declining user background
         */
        $this->mediaMicroservice->declineUserBackground(
            $userProfileRequest
        );

        /**
         * Declining user voice sample
         */
        $this->mediaMicroservice->declineUserVoiceSample(
            $userProfileRequest
        );

        /**
         * Declining user images
         */
        $this->mediaMicroservice->declineUserImages(
            $userProfileRequest
        );

        /**
         * Declining user videos
         */
        $this->mediaMicroservice->declineUserVideos(
            $userProfileRequest
        );

        /**
         * Updating user profile request status
         */
        $this->userProfileRequestRepository->updateRequestStatus(
            $userProfileRequest,
            RequestStatusList::getDeclinedItem()
        );

        /**
         * Updating processing admin
         */
        $this->userProfileRequestRepository->updateAdmin(
            $userProfileRequest,
            AuthService::admin()
        );

        /**
         * Sending user profile request decline email notification
         */
        $this->emailNotificationService->sendUserProfileDeclined(
            $user
        );

        return $this->respondWithSuccess(
            $this->transformItem($userProfileRequest, new UserProfileRequestTransformer),
            trans('validations/api/admin/user/request/profile/declineAll.result.success')
        );
    }
}
