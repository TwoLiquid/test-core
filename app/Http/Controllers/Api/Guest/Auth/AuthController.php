<?php

namespace App\Http\Controllers\Api\Guest\Auth;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Exceptions\MicroserviceException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Auth\Interfaces\AuthControllerInterface;
use App\Http\Requests\Api\Guest\Auth\CheckEmailRequest;
use App\Http\Requests\Api\Guest\Auth\CheckUsernameRequest;
use App\Http\Requests\Api\Guest\Auth\InitializePasswordResetRequest;
use App\Http\Requests\Api\Guest\Auth\LoginRequest;
use App\Http\Requests\Api\Guest\Auth\RegisterEmailVerifyRequest;
use App\Http\Requests\Api\Guest\Auth\RegisterRequest;
use App\Http\Requests\Api\Guest\Auth\ResetPasswordRequest;
use App\Lists\Account\Status\AccountStatusList;
use App\Lists\Gender\GenderList;
use App\Microservices\Auth\AuthMicroservice;
use App\Repositories\Billing\BillingRepository;
use App\Repositories\IpRegistrationList\IpRegistrationListRepository;
use App\Repositories\Place\CountryPlaceRepository;
use App\Repositories\Place\RegionPlaceRepository;
use App\Repositories\User\UserProfileRequestRepository;
use App\Repositories\User\UserRepository;
use App\Services\Admin\AdminNavbarService;
use App\Services\Alert\AlertService;
use App\Services\Auth\AuthService;
use App\Services\File\MediaService;
use App\Services\Notification\EmailNotificationService;
use App\Services\Timezone\TimezoneService;
use App\Services\User\UserProfileRequestService;
use App\Services\User\UserService;
use App\Transformers\Api\Guest\Auth\Register\Form\RegisterFormTransformer;
use Dedicated\GoogleTranslate\TranslateException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Exception;
use Spatie\GoogleTimeZone\Exceptions\GoogleTimeZoneException;

/**
 * Class AuthController
 *
 * @package App\Http\Controllers\Api\Guest\Auth
 */
class AuthController extends BaseController implements AuthControllerInterface
{
    /**
     * @var AdminNavbarService
     */
    protected AdminNavbarService $adminNavbarService;

    /**
     * @var AlertService
     */
    protected AlertService $alertService;

    /**
     * @var AuthMicroservice
     */
    protected AuthMicroservice $authMicroservice;

    /**
     * @var AuthService
     */
    protected AuthService $authService;

    /**
     * @var BillingRepository
     */
    protected BillingRepository $billingRepository;

    /**
     * @var CountryPlaceRepository
     */
    protected CountryPlaceRepository $countryPlaceRepository;

    /**
     * @var EmailNotificationService
     */
    protected EmailNotificationService $emailNotificationService;

    /**
     * @var IpRegistrationListRepository
     */
    protected IpRegistrationListRepository $ipRegistrationListRepository;

    /**
     * @var MediaService
     */
    protected MediaService $mediaService;

    /**
     * @var RegionPlaceRepository
     */
    protected RegionPlaceRepository $regionPlaceRepository;

    /**
     * @var TimezoneService
     */
    protected TimezoneService $timezoneService;

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
     * AuthController constructor
     */
    public function __construct()
    {
        /** @var AdminNavbarService adminNavbarService */
        $this->adminNavbarService = new AdminNavbarService();

        /** @var AlertService alertService */
        $this->alertService = new AlertService();

        /** @var AuthMicroservice authMicroservice */
        $this->authMicroservice = new AuthMicroservice();

        /** @var AuthService authService */
        $this->authService = new AuthService();

        /** @var BillingRepository billingRepository */
        $this->billingRepository = new BillingRepository();

        /** @var CountryPlaceRepository countryPlaceRepository */
        $this->countryPlaceRepository = new CountryPlaceRepository();

        /** @var EmailNotificationService emailNotificationService */
        $this->emailNotificationService = new EmailNotificationService();

        /** @var IpRegistrationListRepository ipRegistrationListRepository */
        $this->ipRegistrationListRepository = new IpRegistrationListRepository();

        /** @var MediaService mediaService */
        $this->mediaService = new MediaService();

        /** @var RegionPlaceRepository regionPlaceRepository */
        $this->regionPlaceRepository = new RegionPlaceRepository();

        /** @var TimezoneService timezoneService */
        $this->timezoneService = new TimezoneService();

        /** @var UserProfileRequestRepository userProfileRequestRepository */
        $this->userProfileRequestRepository = new UserProfileRequestRepository();

        /** @var UserProfileRequestService userProfileRequestService */
        $this->userProfileRequestService = new UserProfileRequestService();

        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();

        /** @var UserService userService */
        $this->userService = new UserService();
    }

    /**
     * @return JsonResponse
     */
    public function getRegisterForm() : JsonResponse
    {
        return $this->respondWithSuccess(
            $this->transformItem([], new RegisterFormTransformer),
            trans('validations/api/guest/auth/getRegisterForm.result.success')
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
            return $this->respondWithErrors([
                'username' => [
                    trans('validations/api/guest/auth/checkUsername.username.unique')
                ]
            ]);
        }

        /**
         * Checking username availability
         */
        $this->authMicroservice->checkUsername(
            $request->input('username')
        );

        return $this->respondWithSuccess([],
            trans('validations/api/guest/auth/checkUsername.result.success')
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
            trans('validations/api/guest/auth/checkEmail.result.success')
        );
    }

    /**
     * @param RegisterRequest $request
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
    public function register(
        RegisterRequest $request
    ) : JsonResponse
    {
        /**
         * Comparing password confirmation fields
         */
        if (!compareStrings(
            $request->input('password'),
            $request->input('password_confirm')
        )) {
            return $this->respondWithError(
                trans('validations/api/guest/auth/register.result.error.credentials.mismatch')
            );
        }

        /**
         * Checking username availability among pending user profile requests
         */
        if ($this->userProfileRequestRepository->existsUsernameForPending(
            $request->input('username')
        )) {
            return $this->respondWithErrors([
                'username' => [
                    trans('validations/api/guest/auth/register.username.unique')
                ]
            ]);
        }

        /**
         * Checking username availability
         */
        $this->authMicroservice->checkUsername(
            $request->input('username')
        );

        /**
         * Checking email availability
         */
        $this->authMicroservice->checkEmail(
            $request->input('email')
        );

        /**
         * Checking is birthdate allowed
         */
        if (!$this->userService->isBirthDateAllowed(
            $request->input('birth_date')
        )) {
            return $this->respondWithErrors([
                'birth_date' => [
                    trans('validations/api/guest/auth/register.result.error.birthDate.young')
                ]
            ]);
        }

        /**
         * Creating user in gateway
         */
        $userResponse = $this->authMicroservice->registerUser(
            $request->input('username'),
            $request->input('email'),
            $request->input('password')
        );

        /**
         * Getting gender
         */
        $genderListItem = GenderList::getItem(
            $request->input('gender_id')
        );

        /**
         * Creating user
         */
        $user = $this->userRepository->store(
            $userResponse->id,
            $request->input('username'),
            $request->input('email'),
            $genderListItem,
            $request->input('birth_date'),
            $request->input('hide_gender'),
            $request->input('hide_age')
        );

        /**
         * Checking user existence
         */
        if (!$user) {
            return $this->respondWithError(
                trans('validations/api/guest/auth/register.result.error.create.user')
            );
        }

        /**
         * Creating user profile request
         */
        $userProfileRequest = $this->userProfileRequestRepository->store(
            $user,
            AccountStatusList::getActive(),
            null,
            $request->input('username'),
            null,
            $request->input('birth_date'),
            null,
            $request->input('description'),
            null
        );

        /**
         * Checking user profile request existence
         */
        if ($userProfileRequest) {

            /**
             * Updating user profile request
             */
            $this->userProfileRequestRepository->updateLanguage(
                $userProfileRequest,
                $user->getLanguage()
            );
        }

        /**
         * Creating user balances
         */
        $this->userService->createUserBalances(
            $user
        );

        /**
         * Getting country place
         */
        $countryPlace = $this->countryPlaceRepository->findByPlaceId(
            $request->input('country_place_id')
        );

        /**
         * Getting region place
         */
        $regionPlace = $this->regionPlaceRepository->findByPlaceId(
            $request->input('region_place_id')
        );

        /**
         * Getting timezone from country place
         */
        $timezone = $this->timezoneService->getByCoordinates(
            $countryPlace->latitude,
            $countryPlace->longitude
        );

        /**
         * Updating user with default country capital timezone
         */
        $this->userRepository->updateTimezone(
            $user,
            $timezone
        );

        /**
         * Creating user billing
         */
        $this->billingRepository->store(
            $user,
            $countryPlace,
            $regionPlace,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null
        );

        /**
         * Attaching notification setting defaults to user
         */
        $this->userService->attachNotificationSettingDefaultValuesToUser(
            $user
        );

        /**
         * Creating alert defaults to user
         */
        $this->alertService->createDefaultsToUser(
            $user
        );

        /**
         * Creating an ip registration list
         */
        $this->ipRegistrationListRepository->store(
            $user,
            request()->ip(),
            false
        );

        /**
         * Releasing user counters caches
         */
        $this->adminNavbarService->releaseAllUserCache();

        /**
         * Releasing new user request counter-caches
         */
        $this->adminNavbarService->releaseProfileRequestCache();

        /**
         * Sending user welcome email notification
         */
        $this->emailNotificationService->sendUserWelcome(
            $user
        );

        return $this->respondWithSuccess([
            'token' => $userResponse->token
        ], trans('validations/api/guest/auth/register.result.success'));
    }

    /**
     * @param LoginRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     */
    public function login(
        LoginRequest $request
    ) : JsonResponse
    {
        /**
         * Checking login
         */
        if ($this->userService->checkLoginIsEmail(
            $request->input('login')
        )) {

            /**
             * Getting user
             */
            $user = $this->userRepository->findByEmail(
                $request->input('login')
            );
        } else {

            /**
             * Getting user
             */
            $user = $this->userRepository->findByUsername(
                $request->input('login')
            );
        }

        /**
         * Checking user existence
         */
        if (!$user) {
            return $this->respondWithError(
                trans('validations/api/guest/auth/login.result.error.find')
            );
        }

        /**
         * Checking the number of user change password attempts
         */
        if (!$this->userService->checkLoginAttempts(
            $user
        )) {
            return $this->respondWithError(
                trans('validations/api/guest/auth/login.result.error.attempts')
            );
        }

        try {

            /**
             * Authorizing user in gateway
             */
            $userResponse = $this->authMicroservice->loginUser(
                $request->input('login'),
                $request->input('password')
            );
        } catch (Exception) {

            /**
             * Decreasing number of login attempts
             */
            if (!$this->userService->decreaseLoginAttempts(
                $user
            )) {

                /**
                 * Updating time of next login change possibility
                 */
                $this->userRepository->establishNextLoginAttempt(
                    $user
                );

                return $this->respondWithError(
                    trans('validations/api/guest/auth/login.result.error.attempts')
                );
            }

            return $this->respondWithError(
                trans('validations/api/guest/auth/login.result.error.credentials') . $user->login_attempts_left
            );
        }

        return $this->respondWithSuccess([
            'token' => $userResponse->token
        ], trans('validations/api/guest/auth/login.result.success'));
    }

    /**
     * @param RegisterEmailVerifyRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function registerEmailVerify(
        RegisterEmailVerifyRequest $request
    ) : JsonResponse
    {
        /**
         * Getting user
         */
        $user = $this->userRepository->findByEmail(
            $request->input('email')
        );

        /**
         * Checking user existence
         */
        if (!$user) {
            return $this->respondWithError(
                trans('validations/api/guest/auth/registerEmailVerify.result.error.find')
            );
        }

        /**
         * Checking user is already verified
         */
        if ($user->email_verified_at) {
            return $this->respondWithError(
                trans('validations/api/guest/auth/registerEmailVerify.result.error.verified')
            );
        }

        /**
         * Checking user and token correlation
         */
        if ($user->email_verify_token != $request->input('token')) {
            return $this->respondWithError(
                trans('validations/api/guest/auth/registerEmailVerify.result.error.mismatch')
            );
        }

        /**
         * Updating user
         */
        $this->userRepository->verifyEmail(
            $user
        );

        return $this->respondWithSuccess([],
            trans('validations/api/guest/auth/registerEmailVerify.result.success')
        );
    }

    /**
     * @param InitializePasswordResetRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function initializePasswordReset(
        InitializePasswordResetRequest $request
    ) : JsonResponse
    {
        /**
         * Getting user
         */
        $user = $this->userRepository->findByEmail(
            $request->input('email')
        );

        /**
         * Checking user existence
         */
        if (!$user) {
            return $this->respondWithError(
                trans('validations/api/guest/auth/initializePasswordReset.result.error.find')
            );
        }

        /**
         * Updating user
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
            trans('validations/api/guest/auth/initializePasswordReset.result.success')
        );
    }

    /**
     * @param ResetPasswordRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function resetPassword(
        ResetPasswordRequest $request
    ) : JsonResponse
    {
        /**
         * Comparing password confirmation fields
         */
        if (!compareStrings(
            $request->input('password'),
            $request->input('password_confirm')
        )) {
            return $this->respondWithError(
                trans('validations/api/guest/auth/resetPassword.result.error.mismatch.credentials')
            );
        }

        /**
         * Getting user
         */
        $user = $this->userRepository->findByEmail(
            $request->input('email')
        );

        /**
         * Checking user existence
         */
        if (!$user) {
            return $this->respondWithError(
                trans('validations/api/guest/auth/resetPassword.result.error.find')
            );
        }

        /**
         * Checking user and token correlation
         */
        if ($user->password_reset_token != $request->input('token')) {
            return $this->respondWithError(
                trans('validations/api/guest/auth/resetPassword.result.error.mismatch.token')
            );
        }

        /**
         * Getting user from gateway
         */
        $this->authMicroservice->updatePassword(
            $request->input('email'),
            $request->input('password')
        );

        return $this->respondWithSuccess([],
            trans('validations/api/guest/auth/resetPassword.result.success')
        );
    }
}
