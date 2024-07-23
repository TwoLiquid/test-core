<?php

namespace App\Http\Controllers\Api\General\Setting;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Exceptions\MicroserviceException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\General\Setting\Interfaces\AccountSettingControllerInterface;
use App\Http\Requests\Api\General\Setting\Account\ChangeEmailRequest;
use App\Http\Requests\Api\General\Setting\Account\ChangeFastOrderRequest;
use App\Http\Requests\Api\General\Setting\Account\ChangePasswordRequest;
use App\Http\Requests\Api\General\Setting\Account\ChangeTimezoneRequest;
use App\Http\Requests\Api\General\Setting\Account\DeactivationRequest;
use App\Http\Requests\Api\General\Setting\Account\DeletionRequest;
use App\Http\Requests\Api\General\Setting\Account\GetBlockedUsersRequest;
use App\Http\Requests\Api\General\Setting\Account\UnsuspendRequest;
use App\Jobs\Notification\Email\Account\AccountEmailVerificationJob;
use App\Lists\Account\Status\AccountStatusList;
use App\Lists\Currency\CurrencyList;
use App\Lists\Language\LanguageList;
use App\Lists\Request\Status\RequestStatusList;
use App\Microservices\Auth\AuthMicroservice;
use App\Repositories\Media\UserAvatarRepository;
use App\Repositories\Place\CityPlaceRepository;
use App\Repositories\User\UserDeactivationRequestRepository;
use App\Repositories\User\UserDeletionRequestRepository;
use App\Repositories\User\UserUnsuspendRequestRepository;
use App\Repositories\User\UserRepository;
use App\Services\Admin\AdminNavbarService;
use App\Services\Auth\AuthService;
use App\Services\Notification\EmailNotificationService;
use App\Services\Place\CityPlaceService;
use App\Services\Timezone\TimezoneService;
use App\Services\User\UserService;
use App\Transformers\Api\General\Setting\Account\AccountSettingTransformer;
use App\Transformers\Api\General\Setting\Account\TimezoneTransformer;
use App\Transformers\Api\General\Setting\Account\UserShortTransformer;
use App\Transformers\Api\General\Setting\Account\UserTransformer;
use Dedicated\GoogleTranslate\TranslateException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Spatie\GoogleTimeZone\Exceptions\GoogleTimeZoneException;
use Exception;

/**
 * Class AccountSettingController
 *
 * @package App\Http\Controllers\Api\General\Setting
 */
class AccountSettingController extends BaseController implements AccountSettingControllerInterface
{
    /**
     * @var AdminNavbarService
     */
    protected AdminNavbarService $adminNavbarService;

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
     * @var TimezoneService
     */
    protected TimezoneService $timezoneService;

    /**
     * @var UserDeactivationRequestRepository
     */
    protected UserDeactivationRequestRepository $userDeactivationRequestRepository;

    /**
     * @var UserDeletionRequestRepository
     */
    protected UserDeletionRequestRepository $userDeletionRequestRepository;

    /**
     * @var UserUnsuspendRequestRepository
     */
    protected UserUnsuspendRequestRepository $userUnsuspendRequestRepository;

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
     * AccountSettingController constructor
     */
    public function __construct()
    {
        /** @var AdminNavbarService adminNavbarService */
        $this->adminNavbarService = new AdminNavbarService();

        /** @var AuthMicroservice authMicroservice */
        $this->authMicroservice = new AuthMicroservice();

        /** @var CityPlaceRepository cityPlaceRepository */
        $this->cityPlaceRepository = new CityPlaceRepository();

        /** @var CityPlaceService cityPlaceService */
        $this->cityPlaceService = new CityPlaceService();

        /** @var EmailNotificationService emailNotificationService */
        $this->emailNotificationService = new EmailNotificationService();

        /** @var TimezoneService timezoneService */
        $this->timezoneService = new TimezoneService();

        /** @var UserDeactivationRequestRepository userDeactivationRequestRepository */
        $this->userDeactivationRequestRepository = new UserDeactivationRequestRepository();

        /** @var UserDeletionRequestRepository userDeletionRequestRepository */
        $this->userDeletionRequestRepository = new UserDeletionRequestRepository();

        /** @var UserUnsuspendRequestRepository userUnsuspendRequestRepository */
        $this->userUnsuspendRequestRepository = new UserUnsuspendRequestRepository();

        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();

        /** @var UserService userService */
        $this->userService = new UserService();

        /** @var UserAvatarRepository userAvatarRepository */
        $this->userAvatarRepository = new UserAvatarRepository();
    }

    /**
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function index() : JsonResponse
    {
        return $this->respondWithSuccess(
            $this->transformItem([],
                new AccountSettingTransformer(
                    AuthService::user()
                )
            )['account_setting'],
            trans('validations/api/general/setting/account/index.result.success')
        );
    }

    /**
     * @param DeactivationRequest $request
     *
     * @return JsonResponse
     *
     * @throws BaseException
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function deactivation(
        DeactivationRequest $request
    ) : JsonResponse
    {
        /**
         * Checking user password
         */
        $this->authMicroservice->checkPassword(
            $request->input('password')
        );

        /**
         * Checking all pending account requests
         */
        $this->userService->checkPendingAccountRequests(
            AuthService::user()
        );

        /**
         * Getting previous account status
         */
        $previousAccountStatusListItem = AccountStatusList::getItem(
            AuthService::user()->account_status_id
        );

        /**
         * Creating user deactivation request
         */
        $userDeactivationRequest = $this->userDeactivationRequestRepository->store(
            AuthService::user(),
            $request->input('reason'),
            AccountStatusList::getDeactivated(),
            $previousAccountStatusListItem
        );

        /**
         * Updating user deactivation request
         */
        $this->userDeactivationRequestRepository->updateLanguage(
            $userDeactivationRequest,
            AuthService::user()->getLanguage()
        );

        /**
         * Releasing user deactivation request counter-caches
         */
        $this->adminNavbarService->releaseUserDeactivationRequestCache();

        return $this->respondWithSuccess(
            $this->transformItem([],
                new AccountSettingTransformer(
                    AuthService::user()
                )
            )['account_setting'],
            trans('validations/api/general/setting/account/deactivation.result.success')
        );
    }

    /**
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function closeDeactivation() : JsonResponse
    {
        /**
         * Getting user deactivation request
         */
        $userDeactivationRequest = $this->userDeactivationRequestRepository->findLastForUser(
            AuthService::user()
        );

        /**
         * Checking user deactivation request existence
         */
        if (!$userDeactivationRequest) {
            return $this->respondWithError(
                trans('validations/api/general/setting/account/closeDeactivation.result.error.find')
            );
        }

        /**
         * Checking is user deactivation request status
         */
        if (!$userDeactivationRequest->getRequestStatus()->isDeclined()) {
            return $this->respondWithError(
                trans('validations/api/general/setting/account/closeDeactivation.result.error.status')
            );
        }

        /**
         * Updating user deactivation request
         */
        $this->userDeactivationRequestRepository->updateShown(
            $userDeactivationRequest,
            true
        );

        return $this->respondWithSuccess([],
            trans('validations/api/general/setting/account/closeDeactivation.result.success')
        );
    }

    /**
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function cancelDeactivation() : JsonResponse
    {
        /**
         * Getting pending user deactivation request
         */
        $userDeactivationRequest = $this->userDeactivationRequestRepository->findPendingForUser(
            AuthService::user()
        );

        /**
         * Checking user deactivation request existence
         */
        if (!$userDeactivationRequest) {
            return $this->respondWithError(
                trans('validations/api/general/setting/account/cancelDeactivation.result.error.exists')
            );
        }

        /**
         * Updating user deactivation request status
         */
        $this->userDeactivationRequestRepository->updateRequestStatus(
            $userDeactivationRequest,
            RequestStatusList::getCanceledItem()
        );

        /**
         * Releasing user deactivation request counter-caches
         */
        $this->adminNavbarService->releaseUserDeactivationRequestCache();

        return $this->respondWithSuccess([],
            trans('validations/api/general/setting/account/cancelDeactivation.result.success')
        );
    }

    /**
     * @param DeletionRequest $request
     *
     * @return JsonResponse
     *
     * @throws BaseException
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function deletion(
        DeletionRequest $request
    ) : JsonResponse
    {
        /**
         * Checking user password
         */
        $this->authMicroservice->checkPassword(
            $request->input('password')
        );

        /**
         * Checking all pending account requests
         */
        $this->userService->checkPendingAccountRequests(
            AuthService::user()
        );

        /**
         * Getting previous account status
         */
        $previousAccountStatusListItem = AccountStatusList::getItem(
            AuthService::user()->account_status_id
        );

        /**
         * Creating user deletion request
         */
         $userDeletionRequest = $this->userDeletionRequestRepository->store(
             AuthService::user(),
             $request->input('reason'),
             AccountStatusList::getDeleted(),
            $previousAccountStatusListItem
        );

        /**
         * Updating user deletion request
         */
         $this->userDeletionRequestRepository->updateLanguage(
             $userDeletionRequest,
             AuthService::user()->getLanguage()
         );

        /**
         * Releasing user deletion request counter-caches
         */
        $this->adminNavbarService->releaseUserDeletionRequestCache();

        return $this->respondWithSuccess(
            $this->transformItem([],
                new AccountSettingTransformer(
                    AuthService::user()
                )
            )['account_setting'],
            trans('validations/api/general/setting/account/deletion.result.success')
        );
    }

    /**
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function closeDeletion() : JsonResponse
    {
        /**
         * Getting user deletion request
         */
        $userDeletionRequest = $this->userDeletionRequestRepository->findLastForUser(
            AuthService::user()
        );

        /**
         * Checking user deletion request existence
         */
        if (!$userDeletionRequest) {
            return $this->respondWithError(
                trans('validations/api/general/setting/account/closeDeletion.result.error.find')
            );
        }

        /**
         * Checking is user deletion request status
         */
        if (!$userDeletionRequest->getRequestStatus()->isDeclined()) {
            return $this->respondWithError(
                trans('validations/api/general/setting/account/closeDeletion.result.error.status')
            );
        }

        /**
         * Updating user deletion request
         */
        $this->userDeletionRequestRepository->updateShown(
            $userDeletionRequest,
            true
        );

        return $this->respondWithSuccess([],
            trans('validations/api/general/setting/account/closeDeletion.result.success')
        );
    }

    /**
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function cancelDeletion() : JsonResponse
    {
        /**
         * Getting pending user deletion request
         */
        $userDeletionRequest = $this->userDeletionRequestRepository->findPendingForUser(
            AuthService::user()
        );

        /**
         * Checking user deletion request existence
         */
        if (!$userDeletionRequest) {
            return $this->respondWithError(
                trans('validations/api/general/setting/account/cancelDeletion.result.error.find')
            );
        }

        /**
         * Updating user deletion request status
         */
        $this->userDeletionRequestRepository->updateRequestStatus(
            $userDeletionRequest,
            RequestStatusList::getCanceledItem()
        );

        /**
         * Releasing user deletion request counter-caches
         */
        $this->adminNavbarService->releaseUserDeletionRequestCache();

        return $this->respondWithSuccess([],
            trans('validations/api/general/setting/account/cancelDeletion.result.success')
        );
    }

    /**
     * @param UnsuspendRequest $request
     *
     * @return JsonResponse
     *
     * @throws BaseException
     * @throws DatabaseException
     */
    public function unsuspend(
        UnsuspendRequest $request
    ) : JsonResponse
    {
        /**
         * Checking all pending account requests
         */
        $this->userService->checkPendingAccountRequests(
            AuthService::user()
        );

        /**
         * Getting previous account status
         */
        $previousAccountStatusListItem = AccountStatusList::getItem(
            AuthService::user()->account_status_id
        );

        /**
         * Creating user unsuspend request
         */
        $userUnsuspendRequest = $this->userUnsuspendRequestRepository->store(
            AuthService::user(),
            $request->input('reason'),
            AccountStatusList::getActive(),
            $previousAccountStatusListItem
        );

        /**
         * Updating user unsuspend request
         */
        $this->userUnsuspendRequestRepository->updateLanguage(
            $userUnsuspendRequest,
            AuthService::user()->getLanguage()
        );

        /**
         * Sending email notification to a user
         */
        $this->emailNotificationService->sendUnsuspensionRequestSubmitted(
            AuthService::user()
        );

        /**
         * Releasing user unsuspension request counter-caches
         */
        $this->adminNavbarService->releaseUserUnsuspensionRequestCache();

        return $this->respondWithSuccess([],
            trans('validations/api/general/setting/account/unsuspend.result.success')
        );
    }

    /**
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function closeUnsuspend() : JsonResponse
    {
        /**
         * Getting user unsuspend request
         */
        $userUnsuspendRequest = $this->userUnsuspendRequestRepository->findLastForUser(
            AuthService::user()
        );

        /**
         * Checking user unsuspend request existence
         */
        if (!$userUnsuspendRequest) {
            return $this->respondWithError(
                trans('validations/api/general/setting/account/closeUnsuspend.result.error.find')
            );
        }

        /**
         * Checking user unsuspend request status
         */
        if (!$userUnsuspendRequest->getRequestStatus()->isDeclined()) {
            return $this->respondWithError(
                trans('validations/api/general/setting/account/closeUnsuspend.result.error.status')
            );
        }

        /**
         * Updating user unsuspend request
         */
        $this->userUnsuspendRequestRepository->updateShown(
            $userUnsuspendRequest,
            true
        );

        return $this->respondWithSuccess([],
            trans('validations/api/general/setting/account/closeUnsuspend.result.success')
        );
    }

    /**
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function cancelUnsuspend() : JsonResponse
    {
        /**
         * Getting pending user deletion request
         */
        $userUnsuspendRequest = $this->userUnsuspendRequestRepository->findPendingForUser(
            AuthService::user()
        );

        /**
         * Checking user unsuspend request existence
         */
        if (!$userUnsuspendRequest) {
            return $this->respondWithError(
                trans('validations/api/general/setting/account/cancelUnsuspend.result.error.find')
            );
        }

        /**
         * Updating user unsuspend request status
         */
        $this->userUnsuspendRequestRepository->updateRequestStatus(
            $userUnsuspendRequest,
            RequestStatusList::getCanceledItem()
        );

        /**
         * Releasing user unsuspension request counter-caches
         */
        $this->adminNavbarService->releaseUserUnsuspensionRequestCache();

        return $this->respondWithSuccess([],
            trans('validations/api/general/setting/account/cancelUnsuspend.result.success')
        );
    }

    /**
     * @param ChangeFastOrderRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function changeFastOrder(
        ChangeFastOrderRequest $request
    ) : JsonResponse
    {
        /**
         * Updating fast order
         */
        $this->userRepository->updateFastOrder(
            AuthService::user(),
            $request->input('enable_fast_order')
        );

        return $this->respondWithSuccess([],
            trans('validations/api/general/setting/account/changeFastOrder.result.success')
        );
    }

    /**
     * @param ChangeEmailRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function changeEmail(
        ChangeEmailRequest $request
    ) : JsonResponse
    {
        /**
         * Checking email last change date validness
         */
        if (!$this->userService->checkEmailLastChangeValid(
            AuthService::user()
        )) {
            return $this->respondWithErrors([
                'email' => [
                    trans('validations/api/general/setting/account/changeEmail.result.error.valid')
                ]
            ]);
        }

        try {

            /**
             * Checking gateway password
             */
            $this->authMicroservice->checkPassword(
                $request->input('password')
            );
        } catch (Exception) {

            /**
             * Decreasing amount of email attempts
             */
            if (!$this->userService->decreaseEmailAttempts(
                AuthService::user()
            )) {

                /**
                 * Updating time of next email change possibility
                 */
                $this->userRepository->establishNextEmailAttempt(
                    AuthService::user()
                );

                return $this->respondWithError(
                    trans('validations/api/general/setting/account/changeEmail.result.error.attempts')
                );
            }

            return $this->respondWithErrors([
                'password'            => [
                    trans('validations/api/general/setting/account/changeEmail.result.error.password')
                ],
                'email_attempts_left' => AuthService::user()->email_attempts_left
            ], trans('validations/api/general/setting/account/changeEmail.result.error.passwordAttempts') . AuthService::user()->email_attempts_left);
        }

        /**
         * Updating user email
         */
        if ($this->userRepository->findByEmail(
            $request->input('email')
        )) {
            return $this->respondWithError(
                trans('validations/api/general/setting/account/changeEmail.result.error.exists')
            );
        }

        /**
         * Updating user email in gateway
         */
        $this->authMicroservice->updateAccountEmail(
            $request->input('email')
        );

        /**
         * Updating user
         */
        $this->userRepository->updateEmail(
            AuthService::user(),
            $request->input('email')
        );

        return $this->respondWithSuccess(
            $this->transformItem(AuthService::user(), new UserTransformer),
            trans('validations/api/general/setting/account/changeEmail.result.success')
        );
    }

    /**
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function resubmitEmail() : JsonResponse
    {
        /**
         * Setting user email verify token
         */
        $user = $this->userRepository->setEmailVerifyToken(
            AuthService::user()
        );

        try {

            /**
             * Sending register email confirm email queue
             */
            AccountEmailVerificationJob::dispatch([
                'locale'             => $user->getLanguage()->iso,
                'email'              => $user->email,
                'email_verify_token' => $user->email_verify_token,
                'email_verify_url'   => config('auth.email.verify.url')
            ]);
        } catch (Exception $exception) {

            /**
             * Adding background error to controller stack
             */
            $this->addBackgroundError(
                new BaseException(
                    'Register e-mail sending error.',
                    $exception->getMessage(),
                    400
                )
            );
        }

        return $this->respondWithSuccess([],
            trans('validations/api/general/setting/account/resubmitEmail.result.success')
        );
    }

    /**
     * @param ChangePasswordRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function changePassword(
        ChangePasswordRequest $request
    ) : JsonResponse
    {
        /**
         * Checking the number of user change password attempts
         */
        if (!$this->userService->checkPasswordAttempts(
            AuthService::user()
        )) {
            return $this->respondWithError(
                trans('validations/api/general/setting/account/changePassword.result.error.attempts')
            );
        }

        try {

            /**
             * Checking gateway password
             */
            $this->authMicroservice->checkPassword(
                $request->input('password')
            );
        } catch (Exception) {

            /**
             * Decreasing number of password attempts
             */
            if (!$this->userService->decreasePasswordAttempts(
                AuthService::user()
            )) {

                /**
                 * Updating time of next email change possibility
                 */
                $this->userRepository->establishNextPasswordAttempt(
                    AuthService::user()
                );

                return $this->respondWithError(
                    trans('validations/api/general/setting/account/changePassword.result.error.attempts')
                );
            }

            return $this->respondWithErrors([
                'password' => [
                    trans('validations/api/general/setting/account/changePassword.result.error.password')
                ],
                'password_attempts_left' => AuthService::user()->password_attempts_left
            ], trans('validations/api/general/setting/account/changePassword.result.error.passwordAttempts') . AuthService::user()->password_attempts_left);
        }

        /**
         * Updating user password
         */
        $this->authMicroservice->updateAccountPassword(
            $request->input('new_password')
        );

        return $this->respondWithSuccess([],
            trans('validations/api/general/setting/account/changePassword.result.success')
        );
    }

    /**
     * @param GetBlockedUsersRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function getBlockedUsers(
        GetBlockedUsersRequest $request
    ) : JsonResponse
    {
        /**
         * Checking search string existence
         */
        if ($request->input('search')) {

            /**
             * Getting blocked users by search
             */
            $blockedUsers = $this->userRepository->getBlockedUsersBySearch(
                AuthService::user(),
                $request->input('search')
            );
        } else {

            /**
             * Getting blocked users
             */
            $blockedUsers = AuthService::user()->blockList;
        }

        return $this->respondWithSuccess(
            $this->transformCollection($blockedUsers, new UserShortTransformer(
                $this->userAvatarRepository->getByUsers(
                    $blockedUsers
                )
            )),
            trans('validations/api/general/setting/account/getBlockedUsers.result.success')
        );
    }

    /**
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function getFastOrderPage() : JsonResponse
    {
        /**
         * Getting user fast order page url
         */
        $fastOrderPageUrl = $this->userService->getFastOrderPageUrl(
            AuthService::user()
        );

        return $this->respondWithSuccess([
            'url' => $fastOrderPageUrl
        ], trans('validations/api/general/setting/account/getFastOrderPage.result.success'));
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function changeLanguage(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting data
         */
        $languageListItem = LanguageList::getItem($id);

        /**
         * Checking language existence
         */
        if (!$languageListItem) {
            return $this->respondWithError(
                trans('validations/api/general/setting/account/changeLanguage.result.error.find')
            );
        }

        /**
         * Updating user language
         */
        $this->userRepository->updateLanguage(
            AuthService::user(),
            $languageListItem
        );

        return $this->respondWithSuccess([],
            trans('validations/api/general/setting/account/changeLanguage.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function changeCurrency(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting data
         */
        $currencyListItem = CurrencyList::getItem($id);

        /**
         * Checking currency existence
         */
        if (!$currencyListItem) {
            return $this->respondWithError(
                trans('validations/api/general/setting/account/changeCurrency.result.error.find')
            );
        }

        /**
         * Updating user currency
         */
        $this->userRepository->updateCurrency(
            AuthService::user(),
            $currencyListItem
        );

        return $this->respondWithSuccess([],
            trans('validations/api/general/setting/account/changeCurrency.result.success')
        );
    }

    /**
     * @param ChangeTimezoneRequest $request
     *
     * @return JsonResponse
     *
     * @throws BaseException
     * @throws DatabaseException
     * @throws GoogleTimeZoneException
     * @throws TranslateException
     */
    public function changeTimezone(
        ChangeTimezoneRequest $request
    ) : JsonResponse
    {
        /**
         * Getting city place
         */
        $cityPlace = $this->cityPlaceRepository->findByPlaceId(
            $request->input('place_id')
        );

        /**
         * Checking city place existence
         */
        if (!$cityPlace) {

            /**
             * Creating city place
             */
            $cityPlace = $this->cityPlaceService->getOrCreate(
                $request->input('place_id')
            );
        }

        /**
         * Updating user
         */
        $this->userRepository->updateTimezone(
            AuthService::user(),
            $cityPlace->timezone
        );

        return $this->respondWithSuccess(
            $this->transformItem(
                $cityPlace->timezone,
                new TimezoneTransformer
            ), trans('validations/api/general/setting/account/changeTimezone.result.success')
        );
    }

    /**
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function reactivateAccount() : JsonResponse
    {
        /**
         * Checking user account status
         */
        if (!AuthService::user()->getAccountStatus()->isDeactivated()) {
            return $this->respondWithError(
                trans('validations/api/general/setting/account/reactivateAccount.result.error.status')
            );
        }

        /**
         * Updating user account status
         */
        $this->userRepository->updateAccountStatus(
            AuthService::user(),
            AccountStatusList::getActive()
        );

        return $this->respondWithSuccess([],
            trans('validations/api/general/setting/account/reactivateAccount.result.success')
        );
    }

    /**
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function restoreAccount() : JsonResponse
    {
        /**
         * Checking user account status
         */
        if (!AuthService::user()->getAccountStatus()->isDeleted()) {
            return $this->respondWithError(
                trans('validations/api/general/setting/account/restoreAccount.result.error.status')
            );
        }

        /**
         * Updating user account status
         */
        $this->userRepository->updateAccountStatus(
            AuthService::user(),
            AccountStatusList::getActive()
        );

        return $this->respondWithSuccess([],
            trans('validations/api/general/setting/account/restoreAccount.result.success')
        );
    }
}
