<?php

namespace App\Http\Controllers\Api\General\Dashboard\Profile;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Exceptions\MicroserviceException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\General\Dashboard\Profile\Interfaces\ProfileControllerInterface;
use App\Http\Requests\Api\General\Dashboard\Profile\CheckEmailRequest;
use App\Http\Requests\Api\General\Dashboard\Profile\CheckUsernameRequest;
use App\Http\Requests\Api\General\Dashboard\Profile\UpdateRequest;
use App\Lists\Account\Status\AccountStatusList;
use App\Lists\Gender\GenderList;
use App\Lists\Request\Status\RequestStatusList;
use App\Microservices\Auth\AuthMicroservice;
use App\Microservices\Media\MediaMicroservice;
use App\Microservices\Media\Responses\UserImageCollectionResponse;
use App\Microservices\Media\Responses\UserVideoCollectionResponse;
use App\Repositories\Media\UserAvatarRepository;
use App\Repositories\Media\UserBackgroundRepository;
use App\Repositories\Media\UserImageRepository;
use App\Repositories\Media\UserVideoRepository;
use App\Repositories\Media\UserVoiceSampleRepository;
use App\Repositories\Place\CityPlaceRepository;
use App\Repositories\User\UserProfileRequestRepository;
use App\Repositories\User\UserRepository;
use App\Services\Auth\AuthService;
use App\Services\File\MediaService;
use App\Services\Place\CityPlaceService;
use App\Services\User\UserProfileRequestService;
use App\Services\User\UserService;
use App\Transformers\Api\General\Dashboard\Profile\ProfilePageTransformer;
use Dedicated\GoogleTranslate\TranslateException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Spatie\GoogleTimeZone\Exceptions\GoogleTimeZoneException;
use Exception;

/**
 * Class ProfileController
 *
 * @package App\Http\Controllers\Api\General\Dashboard\Profile
 */
final class ProfileController extends BaseController implements ProfileControllerInterface
{
    /**
     * @var AuthMicroservice
     */
    protected AuthMicroservice $authMicroservice;

    /**
     * @var CityPlaceRepository
     */
    protected CityPlaceRepository $cityPlaceRepository;

    /**
     * @var CityPlaceService
     */
    protected CityPlaceService $cityPlaceService;

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
     * ProfileController constructor
     */
    public function __construct()
    {
        /** @var AuthMicroservice authMicroservice */
        $this->authMicroservice = new AuthMicroservice();

        /** @var CityPlaceRepository cityPlaceRepository */
        $this->cityPlaceRepository = new CityPlaceRepository();

        /** @var CityPlaceService cityPlaceService */
        $this->cityPlaceService = new CityPlaceService();

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
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function index() : JsonResponse
    {
        /**
         * Getting user
         */
        $user = AuthService::user();

        /**
         * Getting user profile request
         */
        $userProfileRequest = $user->profileRequest;

        /**
         * Getting user dashboard profile
         */
        $userDashboardProfile = $this->userRepository->getUserDashboardProfile(
            $user
        );

        return $this->respondWithSuccess(
            $this->transformItem(
                $userDashboardProfile,
                new ProfilePageTransformer(
                    $this->userRepository->findByIdForAdmin(
                        $user->id
                    ),
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
                    $userProfileRequest ? $this->userAvatarRepository->getByRequests(
                        new Collection([$userProfileRequest])
                    ) : null,
                    $userProfileRequest ? $this->userBackgroundRepository->getByRequests(
                        new Collection([$userProfileRequest])
                    ) : null,
                    $userProfileRequest ? $this->userVoiceSampleRepository->getByRequests(
                        new Collection([$userProfileRequest])
                    ) : null,
                    $userProfileRequest ? $this->userImageRepository->getByRequests(
                        new Collection([$userProfileRequest])
                    ) : null,
                    $userProfileRequest ? $this->userVideoRepository->getByRequests(
                        new Collection([$userProfileRequest])
                    ) : null
                )
            )['profile_page'],
            trans('validations/api/general/dashboard/profile/index.result.success')
        );
    }

    /**
     * @param CheckUsernameRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function checkUsername(
        CheckUsernameRequest $request
    ) : JsonResponse
    {
        /**
         * Checking username availability among pending user profile requests
         */
        if ($this->userProfileRequestRepository->existsUsernameForPending(
            $request->input('username')
        )) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/profile/checkUsername.username.unique')
            );
        }

        /**
         * Checking username availability
         */
        $this->authMicroservice->checkUsername(
            $request->input('username')
        );

        return $this->respondWithSuccess([],
            trans('validations/api/general/dashboard/profile/checkUsername.result.success')
        );
    }

    /**
     * @param CheckEmailRequest $request
     *
     * @return JsonResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function checkEmail(
        CheckEmailRequest $request
    ) : JsonResponse
    {
        /**
         * Checking email availability
         */
        $this->authMicroservice->checkEmail(
            $request->input('email')
        );

        return $this->respondWithSuccess([],
            trans('validations/api/general/dashboard/profile/checkEmail.result.success')
        );
    }

    /**
     * @param UpdateRequest $request
     *
     * @return JsonResponse
     *
     * @throws BaseException
     * @throws DatabaseException
     * @throws GoogleTimeZoneException
     * @throws GuzzleException
     * @throws TranslateException
     */
    public function update(
        UpdateRequest $request
    ) : JsonResponse
    {
        /**
         * Getting pending user profile request for user
         */
        if ($this->userProfileRequestRepository->findPendingForUser(
            AuthService::user()
        )) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/profile/update.result.error.profileRequest.pending')
            );
        }

        /**
         * Validating uploaded user voice sample
         */
        if ($request->has('voice_sample')) {
            $this->mediaService->validateUserVoiceSample(
                $request->input('voice_sample')['content'],
                $request->input('voice_sample')['mime'],
            );
        }

        /**
         * Validating uploaded user avatar
         */
        if ($request->has('avatar')) {
            $this->mediaService->validateUserAvatar(
                $request->input('avatar')['content'],
                $request->input('avatar')['mime'],
            );
        }

        /**
         * Validating an uploaded user background
         */
        if ($request->has('background')) {
            $this->mediaService->validateUserBackground(
                $request->input('background')['content'],
                $request->input('background')['mime'],
            );
        }

        /**
         * Validating uploaded user album
         */
        if ($request->has('album')) {
            $this->userService->validateUserAlbum(
                $request->input('album')
            );
        }

        /**
         * Getting gender
         */
        $genderListItem = GenderList::getItem(
            $request->input('gender_id')
        );

        /**
         * Checking username availability
         */
        if ($this->userRepository->checkUsernameUniqueness(
            AuthService::user(),
            $request->input('username')
        )) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/profile/update.result.error.uniqueness.username')
            );
        }

        /**
         * Checking username availability among user pending profile requests
         */
        if ($this->userProfileRequestRepository->existsUsernameForPending(
            $request->input('username')
        )) {
            return $this->respondWithSuccess([],
                trans('validations/api/general/dashboard/profile/update.result.error.uniqueness.username')
            );
        }

        /**
         * Checking is birthdate allowed
         */
        if (!$this->userService->isBirthDateAllowed(
            $request->input('birth_date')
        )) {
            return $this->respondWithErrors([
                'birth_date' => [
                    trans('validations/api/general/dashboard/profile/update.result.error.birthDate.young')
                ]
            ]);
        }

        /**
         * Preparing current city place id variable
         */
        $currentCityPlace = null;

        /**
         * Checking current city place id existence
         */
        if ($request->input('current_city_place_id')) {

            /**
             * Getting city place
             */
            $currentCityPlace = $this->cityPlaceRepository->findByPlaceId(
                $request->input('current_city_place_id')
            );

            /**
             * Checking city place existence
             */
            if (!$currentCityPlace) {

                /**
                 * Creating city place
                 */
                $currentCityPlace = $this->cityPlaceService->getOrCreate(
                    $request->input('current_city_place_id')
                );
            }
        }

        /**
         * Updating user
         */
        $this->userRepository->updateForDashboard(
            AuthService::user(),
            $genderListItem,
            $currentCityPlace,
            $request->input('hide_gender'),
            $request->input('hide_age'),
            $request->input('hide_location'),
            $request->input('top_vybers'),
            $request->input('hide_reviews')
        );

        /**
         * Updating user personality traits
         */
        $this->userService->updateUserPersonalityTraits(
            AuthService::user(),
            $request->input('personality_traits_ids')
        );

        /**
         * Updating user languages
         */
        $this->userService->updateLanguagesToUser(
            AuthService::user(),
            $request->input('languages')
        );

        /**
         * Checking is user account status pending
         */
        if (AuthService::user()
            ->getAccountStatus()
            ->isPending()
        ) {

            /**
             * Creating user profile request
             */
            $userProfileRequest = $this->userProfileRequestRepository->store(
                AuthService::user(),
                AccountStatusList::getActive(),
                AccountStatusList::getPending(),
                $request->input('username'),
                null,
                $request->input('birth_date'),
                null,
                $request->input('description'),
                null
            );
        } else {

            /**
             * Creating user profile request
             */
            $userProfileRequest = $this->userProfileRequestService->createIfHasChanges(
                AuthService::user(),
                $request->input('username'),
                $request->input('birth_date'),
                $request->input('description'),
                $request->has('voice_sample'),
                $request->has('avatar'),
                $request->has('background'),
                $this->userProfileRequestService->albumHasImages($request->input('album')),
                $this->userProfileRequestService->albumHasVideos($request->input('album')),
            );
        }

        /**
         * Checking user profile request existence
         */
        if ($userProfileRequest) {

            /**
             * Checking voice sample existence
             */
            if ($request->input('voice_sample')) {

                try {

                    /**
                     * Uploading user voice sample
                     */
                    $voiceSampleResponse = $this->mediaMicroservice->storeUserVoiceSample(
                        AuthService::user(),
                        $userProfileRequest,
                        $request->input('voice_sample')['content'],
                        $request->input('voice_sample')['mime'],
                        $request->input('voice_sample')['extension']
                    );

                    /**
                     * Updating user profile request
                     */
                    $userProfileRequest = $this->userProfileRequestRepository->updateVoiceSample(
                        $userProfileRequest,
                        $voiceSampleResponse->id,
                        AuthService::user()->voice_sample_id
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

            /**
             * Checking avatar existence
             */
            if ($request->input('avatar')) {

                try {

                    /**
                     * Uploading user avatar
                     */
                    $avatarResponse = $this->mediaMicroservice->storeUserAvatar(
                        AuthService::user(),
                        $userProfileRequest,
                        $request->input('avatar')['content'],
                        $request->input('avatar')['mime'],
                        $request->input('avatar')['extension']
                    );

                    /**
                     * Updating user profile request
                     */
                    $userProfileRequest = $this->userProfileRequestRepository->updateAvatar(
                        $userProfileRequest,
                        $avatarResponse->id,
                        AuthService::user()->voice_sample_id
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

            /**
             * Checking background existence
             */
            if ($request->input('background')) {

                try {

                    /**
                     * Uploading a user background
                     */
                    $backgroundResponse = $this->mediaMicroservice->storeUserBackground(
                        AuthService::user(),
                        $userProfileRequest,
                        $request->input('background')['content'],
                        $request->input('background')['mime'],
                        $request->input('background')['extension']
                    );

                    /**
                     * Updating user profile request
                     */
                    $userProfileRequest = $this->userProfileRequestRepository->updateBackground(
                        $userProfileRequest,
                        $backgroundResponse->id,
                        AuthService::user()->background_id
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

            /**
             * Checking album existence
             */
            if ($request->input('album')) {

                try {

                    /** @var UserImageCollectionResponse $userImageCollectionResponse */
                    $userImageCollectionResponse = $this->userProfileRequestService->uploadImages(
                        $userProfileRequest,
                        $request->input('album')
                    );

                    /**
                     * Checking uploaded images existence
                     */
                    if ($userImageCollectionResponse &&
                        $userImageCollectionResponse->images->count()
                    ) {

                        /**
                         * Updating user profile request
                         */
                        $userProfileRequest = $this->userProfileRequestRepository->updateAlbum(
                            $userProfileRequest,
                            $this->userProfileRequestService->getUpdatedImagesIds(
                                AuthService::user()->images_ids,
                                $request->input('deleted_images_ids'),
                                $userImageCollectionResponse->images
                                    ->pluck('id')
                                    ->values()
                                    ->toArray()
                            ),
                            AuthService::user()->images_ids,
                            null,
                            null
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

                try {

                    /** @var UserVideoCollectionResponse $userVideoCollectionResponse */
                    $userVideoCollectionResponse = $this->userProfileRequestService->uploadVideos(
                        $userProfileRequest,
                        $request->input('album')
                    );

                    /**
                     * Checking uploaded videos existence
                     */
                    if ($userVideoCollectionResponse &&
                        $userVideoCollectionResponse->videos->count()
                    ) {

                        /**
                         * Updating user profile request
                         */
                        $this->userProfileRequestRepository->updateAlbum(
                            $userProfileRequest,
                            null,
                            null,
                            $this->userProfileRequestService->getUpdatedVideosIds(
                                AuthService::user()->videos_ids,
                                $request->input('deleted_videos_ids'),
                                $userVideoCollectionResponse->videos
                                    ->pluck('id')
                                    ->values()
                                    ->toArray()
                            ),
                            AuthService::user()->videos_ids
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
        }

        /**
         * Checking deleted avatar existence
         */
        if ($request->input('deleted_avatar_id')) {

            /**
             * Updating user
             */
            $this->userService->deleteAvatarId(
                AuthService::user(),
                $request->input('deleted_avatar_id')
            );
        }

        /**
         * Checking deleted voice sample existence
         */
        if ($request->input('deleted_voice_sample_id')) {

            /**
             * Updating user
             */
            $this->userService->deleteVoiceSampleId(
                AuthService::user(),
                $request->input('deleted_voice_sample_id')
            );
        }

        /**
         * Checking deleted background existence
         */
        if ($request->input('deleted_background_id')) {

            /**
             * Updating user
             */
            $this->userService->deleteBackgroundId(
                AuthService::user(),
                $request->input('deleted_background_id')
            );
        }

        /**
         * Checking deleted images existence
         */
        if ($request->input('deleted_images_ids')) {

            /**
             * Updating user
             */
            $this->userService->deleteImagesIds(
                AuthService::user(),
                $request->input('deleted_images_ids')
            );
        }

        /**
         * Checking deleted videos existence
         */
        if ($request->input('deleted_videos_ids')) {

            /**
             * Updating user
             */
            $this->userService->deleteVideosIds(
                AuthService::user(),
                $request->input('deleted_videos_ids')
            );
        }

        /**
         * Getting user
         */
        $user = AuthService::user();

        /**
         * Getting user dashboard profile
         */
        $userDashboardProfile = $this->userRepository->getUserDashboardProfile(
            $user
        );

        return $this->respondWithSuccess(
            $this->transformItem(
                $userDashboardProfile,
                new ProfilePageTransformer(
                    $user,
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
                    $userProfileRequest ? $this->userAvatarRepository->getByRequests(
                        new Collection([$userProfileRequest])
                    ) : null,
                    $userProfileRequest ? $this->userBackgroundRepository->getByRequests(
                        new Collection([$userProfileRequest])
                    ) : null,
                    $userProfileRequest ? $this->userVoiceSampleRepository->getByRequests(
                        new Collection([$userProfileRequest])
                    ) : null,
                    $userProfileRequest ? $this->userImageRepository->getByRequests(
                        new Collection([$userProfileRequest])
                    ) : null,
                    $userProfileRequest ? $this->userVideoRepository->getByRequests(
                        new Collection([$userProfileRequest])
                    ) : null
                )
            )['profile_page'],
            trans('validations/api/general/dashboard/profile/update.result.success')
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
         * Getting pending user profile request
         */
        $userProfileRequest = $this->userProfileRequestRepository->findLastForUser(
            AuthService::user()
        );

        /**
         * Checking user profile request existence
         */
        if (!$userProfileRequest) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/profile/close.result.error.find')
            );
        }

        /**
         * Checking user profile request status
         */
        if (!$userProfileRequest->getRequestStatus()->isDeclined()) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/profile/close.result.error.status')
            );
        }

        /**
         * Updating user profile request
         */
        $this->userProfileRequestRepository->updateShown(
            $userProfileRequest,
            true
        );

        return $this->respondWithSuccess([],
            trans('validations/api/general/dashboard/profile/close.result.success')
        );
    }

    /**
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     */
    public function cancel() : JsonResponse
    {
        /**
         * Getting user pending profile request
         */
        $userProfileRequest = $this->userProfileRequestRepository->findPendingForUser(
            AuthService::user()
        );

        /**
         * Checking user profile request existence
         */
        if (!$userProfileRequest) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/profile/cancel.result.error.pending.request')
            );
        }

        /**
         * Checking is a user account pending
         */
        if (AuthService::user()
            ->getAccountStatus()
            ->isPending()
        ) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/profile/cancel.result.error.pending.account')
            );
        }

        try {

            /**
             * Declining user profile request images
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
             * Declining user profile request videos
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

        try {

            /**
             * Declining user profile request avatar
             */
            $this->mediaMicroservice->declineUserAvatar(
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
             * Declining user profile request background
             */
            $this->mediaMicroservice->declineUserBackground(
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
             * Declining user profile request voice sample
             */
            $this->mediaMicroservice->declineUserVoiceSample(
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

        /**
         * Updating user profile request status
         */
        $this->userProfileRequestRepository->updateRequestStatus(
            $userProfileRequest,
            RequestStatusList::getCanceledItem()
        );

        return $this->respondWithSuccess([],
            trans('validations/api/general/dashboard/profile/cancel.result.success')
        );
    }
}
