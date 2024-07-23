<?php

namespace App\Http\Controllers\Api\Admin\User\User;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Exceptions\MicroserviceException;
use App\Exceptions\ValidationException;
use App\Http\Controllers\Api\Admin\User\User\Interfaces\UserControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\User\User\DestroyRequest;
use App\Http\Requests\Api\Admin\User\User\IndexRequest;
use App\Http\Requests\Api\Admin\User\User\ShowRequest;
use App\Http\Requests\Api\Admin\User\User\UpdateRequest;
use App\Lists\Account\Status\AccountStatusList;
use App\Lists\Currency\CurrencyList;
use App\Lists\Gender\GenderList;
use App\Lists\Language\LanguageList;
use App\Lists\User\Balance\Status\UserBalanceStatusList;
use App\Lists\User\Balance\Type\UserBalanceTypeList;
use App\Microservices\Auth\AuthMicroservice;
use App\Microservices\Media\MediaMicroservice;
use App\Repositories\Media\UserAvatarRepository;
use App\Repositories\Media\UserBackgroundRepository;
use App\Repositories\Media\UserImageRepository;
use App\Repositories\Media\UserVideoRepository;
use App\Repositories\Media\UserVoiceSampleRepository;
use App\Repositories\Place\CityPlaceRepository;
use App\Repositories\User\UserBalanceRepository;
use App\Repositories\User\UserProfileRequestRepository;
use App\Repositories\User\UserRepository;
use App\Services\Auth\AuthService;
use App\Services\File\MediaService;
use App\Services\Notification\EmailNotificationService;
use App\Services\Place\CityPlaceService;
use App\Services\User\UserService;
use App\Transformers\Api\Admin\User\User\AffiliateListTransformer;
use App\Transformers\Api\Admin\User\User\BuyerListTransformer;
use App\Transformers\Api\Admin\User\User\Form\UserFormTransformer;
use App\Transformers\Api\Admin\User\User\SellerListTransformer;
use App\Transformers\Api\Admin\User\User\UserAllListTransformer;
use App\Transformers\Api\Admin\User\User\UserFilterFormTransformer;
use App\Transformers\Api\Admin\User\User\UserPageTransformer;
use Dedicated\GoogleTranslate\TranslateException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Spatie\GoogleTimeZone\Exceptions\GoogleTimeZoneException;
use Exception;

/**
 * Class UserController
 *
 * @package App\Http\Controllers\Api\Admin\User\User
 */
final class UserController extends BaseController implements UserControllerInterface
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
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * @var UserBalanceRepository
     */
    protected UserBalanceRepository $userBalanceRepository;

    /**
     * @var UserProfileRequestRepository
     */
    protected UserProfileRequestRepository $userProfileRequestRepository;

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
     * UserController constructor
     */
    public function __construct()
    {
        /** @var AuthMicroservice authMicroservice */
        $this->authMicroservice = new AuthMicroservice();

        /** @var CityPlaceRepository cityPlaceRepository */
        $this->cityPlaceRepository = new CityPlaceRepository();

        /** @var CityPlaceService cityPlaceService */
        $this->cityPlaceService = new CityPlaceService();

        /** @var EmailNotificationService emailNotificationService */
        $this->emailNotificationService = new EmailNotificationService();

        /** @var MediaMicroservice mediaMicroservice */
        $this->mediaMicroservice = new MediaMicroservice();

        /** @var MediaService mediaService */
        $this->mediaService = new MediaService();

        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();

        /** @var UserBalanceRepository userBalanceRepository */
        $this->userBalanceRepository = new UserBalanceRepository();

        /** @var UserProfileRequestRepository userProfileRequestRepository */
        $this->userProfileRequestRepository = new UserProfileRequestRepository();

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
         * Getting a user balance type
         */
        $userBalanceTypeListItem = UserBalanceTypeList::getItem(
            $request->input('user_balance_type_id')
        );

        /**
         * Getting a correct transformer
         */
        $userTransformer = new UserAllListTransformer;

        /**
         * Checking a user balance type existence
         */
        if ($userBalanceTypeListItem) {

            /**
             * Getting a correct transformer
             */
            if ($userBalanceTypeListItem->isBuyer()) {
                $userTransformer = new BuyerListTransformer;
            } elseif ($userBalanceTypeListItem->isSeller()) {
                $userTransformer = new SellerListTransformer;
            } elseif ($userBalanceTypeListItem->isAffiliate()) {
                $userTransformer = new AffiliateListTransformer;
            }
        }

        /**
         * Checking pagination existence
         */
        if ($request->input('paginated') === true) {

            /**
             * Getting users with pagination
             */
            $users = $this->userRepository->getAllPaginatedFiltered(
                $request->input('user_id'),
                $request->input('username'),
                $request->input('first_name'),
                $request->input('last_name'),
                $request->input('country_id'),
                $request->input('followers'),
                $request->input('date_from'),
                $request->input('date_to'),
                $request->input('statuses_ids'),
                $request->input('user_balance_type_id'),
                $request->input('sort_by'),
                $request->input('sort_order'),
                $request->input('per_page'),
                $request->input('page')
            );

            return $this->setPagination($users)->respondWithSuccess(
                $this->transformItem([], new UserFilterFormTransformer) +
                $this->transformCollection($users, $userTransformer),
                trans('validations/api/admin/user/index.result.success')
            );
        }

        /**
         * Getting users with pagination
         */
        $users = $this->userRepository->getAllFiltered(
            $request->input('user_id'),
            $request->input('username'),
            $request->input('first_name'),
            $request->input('last_name'),
            $request->input('country_id'),
            $request->input('followers'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('statuses_ids'),
            $request->input('user_balance_type_id'),
            $request->input('sort_by'),
            $request->input('sort_order')
        );

        return $this->respondWithSuccess(
            $this->transformItem([], new UserFilterFormTransformer) +
            $this->transformCollection($users, $userTransformer),
            trans('validations/api/admin/user/index.result.success')
        );
    }

    /**
     * @param int $id
     * @param ShowRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function show(
        int $id,
        ShowRequest $request
    ) : JsonResponse
    {
        /**
         * Getting user
         */
        $user = $this->userRepository->findByIdForAdmin($id);

        if (!$user) {
            return $this->respondNotFound(
                trans('validations/api/admin/user/show.result.error.find')
            );
        }

        $form = [];
        if ($request->input('form') === true) {
            $form = $this->transformItem([], new UserFormTransformer(
                $user
            ));
        }

        return $this->respondWithSuccess(
            $this->transformItem($user, new UserPageTransformer(
                $request->input('requests'),
                $this->userAvatarRepository->getByUsers(
                    new Collection([$user])
                ),
                $this->userBackgroundRepository->getByUsers(
                    new Collection([$user])
                ),
                $this->userVoiceSampleRepository->getByUsers(
                    new Collection([$user])
                ),
                $this->userImageRepository->getByUsers(
                    new Collection([$user])
                ),
                $this->userVideoRepository->getByUsers(
                    new Collection([$user])
                )
            )) + $form,
            trans('validations/api/admin/user/show.result.success')
        );
    }

    /**
     * @param int $id
     * @param UpdateRequest $request
     *
     * @return JsonResponse
     *
     * @throws BaseException
     * @throws DatabaseException
     * @throws GoogleTimeZoneException
     * @throws GuzzleException
     * @throws MicroserviceException
     * @throws TranslateException
     */
    public function update(
        int $id,
        UpdateRequest $request
    ) : JsonResponse
    {
        /**
         * Finding user
         */
        $user = $this->userRepository->findByIdForAdmin($id);

        /**
         * Checking user existence
         */
        if (!$user) {
            return $this->respondWithError(
                trans('validations/api/admin/user/user/update.result.error.find.user')
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
         * Validating uploaded user avatar
         */
        if ($request->has('avatar')) {
            $avatarFile = $request->input('avatar');

            $this->mediaService->validateUserAvatar(
                $avatarFile['content'],
                $avatarFile['mime'],
            );
        }

        /**
         * Validating an uploaded user background
         */
        if ($request->has('background')) {
            $backgroundFile = $request->input('background');

            $this->mediaService->validateUserBackground(
                $backgroundFile['content'],
                $backgroundFile['mime'],
            );
        }

        /**
         * Validating uploaded user voice sample
         */
        if ($request->has('voice_sample')) {
            $voiceSampleFile = $request->input('voice_sample');

            $this->mediaService->validateUserVoiceSample(
                $voiceSampleFile['content'],
                $voiceSampleFile['mime'],
            );
        }

        /**
         * Getting account status
         */
        $accountStatusListItem = AccountStatusList::getItem(
            $request->input('account_status_id')
        );

        /**
         * Getting buyer status
         */
        $buyerStatusListItem = UserBalanceStatusList::getItem(
            $request->input('buyer_status_id')
        );

        /**
         * Getting seller status
         */
        $sellerStatusListItem = UserBalanceStatusList::getItem(
            $request->input('seller_status_id')
        );

        /**
         * Getting affiliate status
         */
        $affiliateStatusListItem = UserBalanceStatusList::getItem(
            $request->input('affiliate_status_id')
        );

        /**
         * Getting language
         */
        $languageListItem = LanguageList::getItem(
            $request->input('language_id')
        );

        /**
         * Getting currency
         */
        $currencyListItem = CurrencyList::getItem(
            $request->input('currency_id')
        );

        /**
         * Getting gender
         */
        $genderListItem = GenderList::getItem(
            $request->input('gender_id')
        );

        /**
         * Getting timezone city place
         */
        $timezoneCityPlace = $request->input('timezone_city_place_id') ? $this->cityPlaceService->getOrCreate(
            $request->input('timezone_city_place_id')
        ) : null;

        /**
         * Getting time
         */
        $timezone = $timezoneCityPlace ?
            $timezoneCityPlace->timezone :
            $user->timezone;

        /**
         * Getting current city place
         */
        $currentCityPlace = $request->input('current_city_place_id') ? $this->cityPlaceService->getOrCreate(
            $request->input('current_city_place_id')
        ) : null;

        /**
         * Checking username validness
         */
        if ($this->userRepository->checkUsernameUniqueness(
            $user,
            $request->input('username')
        )) {
            return $this->respondWithError(
                trans('validations/api/admin/user/user/update.result.error.uniqueness.username.user')
            );
        }

        /**
         * Checking email validness
         */
        if ($this->userRepository->checkEmailUniqueness(
            $user,
            $request->input('email')
        )) {
            return $this->respondWithError(
                trans('validations/api/admin/user/user/update.result.error.uniqueness.email')
            );
        }

        /**
         * Checking user profile request username existence
         */
        if ($this->userProfileRequestRepository->existsUsernameForPending(
            $request->input('username')
        )) {
            return $this->respondWithError(
                trans('validations/api/admin/user/user/update.result.error.uniqueness.username.pending')
            );
        }

        /**
         * Update user in gateway
         */
        $this->authMicroservice->updateUserByAdmin(
            $user,
            $request->input('username'),
            $request->input('email'),
            $request->input('password')
        );

        /**
         * Updating user
         */
        $this->userRepository->updateForAdmin(
            $user,
            $accountStatusListItem,
            $languageListItem,
            $currencyListItem,
            $timezone,
            $currentCityPlace,
            $genderListItem,
            $request->input('username'),
            $request->input('email'),
            $request->input('hide_gender'),
            $request->input('birth_date'),
            $request->input('hide_age'),
            $request->input('verified_partner'),
            $request->input('streamer_badge'),
            $request->input('streamer_milestone'),
            $request->input('hide_location'),
            $request->input('description'),
            $request->input('receive_news')
        );

        /**
         * Updating user-buyer balance
         */
        $this->userBalanceRepository->updateStatus(
            $user->getBuyerBalance(),
            $buyerStatusListItem
        );

        /**
         * Updating user-seller balance
         */
        $this->userBalanceRepository->updateStatus(
            $user->getSellerBalance(),
            $sellerStatusListItem
        );

        /**
         * Updating user-affiliate balance
         */
        $this->userBalanceRepository->updateStatus(
            $user->getAffiliateBalance(),
            $affiliateStatusListItem
        );

        /**
         * Checking account status is suspended
         */
        if ($accountStatusListItem->isSuspended()) {

            /**
             * Updating client profile
             */
            $this->userRepository->updateSuspendInformation(
                $user,
                $request->input('suspend_reason')
            );

            /**
             * Sending email notification to a user
             */
            $this->emailNotificationService->sendSuspension(
                $user
            );
        }

        /**
         * Updating user personality traits
         */
        $this->userService->updateUserPersonalityTraits(
            $user,
            $request->input('personality_traits_ids')
        );

        /**
         * Updating user languages
         */
        $this->userService->updateLanguagesToUser(
            $user,
            $request->input('languages')
        );

        if ($request->input('album')) {

            try {

                /**
                 * Uploading user album images
                 */
                 $this->userService->uploadUserImages(
                    $user,
                    $request->input('album')
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
                 * Uploading user album videos
                 */
                $this->userService->uploadUserVideos(
                    $user,
                    $request->input('album')
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

        if ($request->input('avatar')) {
            $avatarFile = $request->input('avatar');

            try {

                /**
                 * Uploading new user avatar
                 */
                $this->mediaMicroservice->storeUserAvatar(
                    $user,
                    null,
                    $avatarFile['content'],
                    $avatarFile['mime'],
                    $avatarFile['extension']
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

        if ($request->input('background')) {
            $backgroundFile = $request->input('background');

            try {

                /**
                 * Uploading a new user background
                 */
                $this->mediaMicroservice->storeUserBackground(
                    $user,
                    null,
                    $backgroundFile['content'],
                    $backgroundFile['mime'],
                    $backgroundFile['extension']
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

        if ($request->input('voice_sample')) {
            $voiceSample = $request->input('voice_sample');

            try {

                /**
                 * Uploading new user voice sample
                 */
                $this->mediaMicroservice->storeUserVoiceSample(
                    $user,
                    null,
                    $voiceSample['content'],
                    $voiceSample['mime'],
                    $voiceSample['extension']
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

        if ($request->input('password')) {

            /**
             * Getting user from gateway
             */
            $this->authMicroservice->updatePassword(
                $user->email,
                $request->input('password')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($user, new UserPageTransformer(
                true,
                $this->userAvatarRepository->getByUsers(
                    new Collection([$user])
                ),
                $this->userBackgroundRepository->getByUsers(
                    new Collection([$user])
                ),
                $this->userVoiceSampleRepository->getByUsers(
                    new Collection([$user])
                ),
                $this->userImageRepository->getByUsers(
                    new Collection([$user])
                ),
                $this->userVideoRepository->getByUsers(
                    new Collection([$user])
                )
            )), trans('validations/api/admin/user/user/update.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function initializePasswordReset(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting user
         */
        $user = $this->userRepository->findByIdForAdmin($id);

        if (!$user) {
            return $this->respondWithError(
                trans('validations/api/admin/user/initializePasswordReset.result.error.find')
            );
        }

        /**
         * Getting user from gateway
         */
        $user = $this->userRepository->setPasswordResetToken(
            $user
        );

        /**
         * Sending account password reset email notification
         */
        $this->emailNotificationService->sendAccountPasswordReset(
            $user
        );

        return $this->respondWithSuccess([],
            trans('validations/api/admin/user/initializePasswordReset.result.success')
        );
    }

    /**
     * @param int $id
     * @param DestroyRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws ValidationException
     */
    public function destroy(
        int $id,
        DestroyRequest $request
    ) : JsonResponse
    {
        /**
         * Checking admin has super rights
         */
        if (!AuthService::admin()->hasFullAccess()) {
            return $this->respondWithError(
                trans('validations/api/admin/user/user/destroy.result.error.super')
            );
        }

        try {

            /**
             * Checking admin password
             */
            $this->authMicroservice->checkPassword(
                $request->input('password')
            );
        } catch (Exception $exception) {
            throw new ValidationException(
                method_exists($exception, 'getValidationErrors') ?
                    $exception->getValidationErrors()['message'] :
                    trans('validations/api/admin/vybe/destroy.result.error.password'),
                'password'
            );
        }

        /**
         * Getting user
         */
        $user = $this->userRepository->findById($id);

        /**
         * Checking user existence
         */
        if (!$user) {
            return $this->respondWithError(
                trans('validations/api/admin/user/user/destroy.result.error.find')
            );
        }

        /**
         * Deleting user
         */
        $this->userService->delete(
            $user
        );

        return $this->respondWithSuccess([],
            trans('validations/api/admin/user/user/destroy.result.success')
        );
    }
}
