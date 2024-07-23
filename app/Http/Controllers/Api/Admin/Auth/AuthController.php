<?php

namespace App\Http\Controllers\Api\Admin\Auth;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Exceptions\MicroserviceException;
use App\Http\Controllers\Api\Admin\Auth\Interfaces\AuthControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\Auth\EnableTwoFactorRequest;
use App\Http\Requests\Api\Admin\Auth\ExecuteTwoFactorRequest;
use App\Http\Requests\Api\Admin\Auth\GetTwoFactorRequest;
use App\Http\Requests\Api\Admin\Auth\LoginRequest;
use App\Http\Requests\Api\Admin\Auth\PasswordResetRequest;
use App\Http\Requests\Api\Admin\Auth\PasswordSetupRequest;
use App\Microservices\Auth\AuthMicroservice;
use App\Repositories\Admin\AdminAuthProtectionRepository;
use App\Repositories\Admin\AdminRepository;
use App\Services\Auth\AuthService;
use App\Services\Google\Google2faService;
use App\Services\Notification\EmailNotificationService;
use App\Transformers\Api\Admin\Auth\AdminTransformer;
use App\Transformers\Api\Admin\Auth\TwoFactorTransformer;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;

/**
 * Class AuthController
 *
 * @package App\Http\Controllers\Api\Admin\Auth
 */
final class AuthController extends BaseController implements AuthControllerInterface
{
    /**
     * @var AdminAuthProtectionRepository
     */
    protected AdminAuthProtectionRepository $adminAuthProtectionRepository;

    /**
     * @var AdminRepository
     */
    protected AdminRepository $adminRepository;

    /**
     * @var AuthMicroservice
     */
    protected AuthMicroservice $authMicroservice;

    /**
     * @var AuthService
     */
    protected AuthService $authService;

    /**
     * @var EmailNotificationService
     */
    protected EmailNotificationService $emailNotificationService;

    /**
     * @var Google2faService
     */
    protected Google2faService $google2faService;

    /**
     * AuthController constructor
     */
    public function __construct()
    {
        /** @var AdminAuthProtectionRepository adminAuthProtectionRepository */
        $this->adminAuthProtectionRepository = new AdminAuthProtectionRepository();

        /** @var AdminRepository adminRepository */
        $this->adminRepository = new AdminRepository();

        /** @var AuthMicroservice authMicroservice */
        $this->authMicroservice = new AuthMicroservice();

        /** @var AuthService authService */
        $this->authService = new AuthService();

        /** @var EmailNotificationService emailNotificationService */
        $this->emailNotificationService = new EmailNotificationService();

        /** @var Google2faService google2faService */
        $this->google2faService = new Google2faService();
    }

    /**
     * @param LoginRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function login(
        LoginRequest $request
    ) : JsonResponse
    {
        /**
         * Getting admin
         */
        $admin = $this->adminRepository->findByEmail(
            $request->input('email')
        );

        /**
         * Checking admin existence
         */
        if (!$admin) {
            return $this->respondWithError(
                trans('validations/api/admin/auth/login.result.error.find')
            );
        }

        /**
         * Authorizing admin in gateway
         */
        $this->authMicroservice->retrieveAdmin(
            $request->input('email'),
            $request->input('password')
        );

        if ($admin->isInitialPassword()) {
            $admin = $this->adminRepository->setPasswordResetToken(
                $admin
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($admin, new AdminTransformer),
            trans('validations/api/admin/auth/login.result.success')
        );
    }

    /**
     * @param GetTwoFactorRequest $request
     *
     * @return JsonResponse
     *
     * @throws BaseException
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function getTwoFactor(
        GetTwoFactorRequest $request
    ) : JsonResponse
    {
        /**
         * Getting admin
         */
        $admin = $this->adminRepository->findByEmail(
            $request->input('email')
        );

        /**
         * Checking admin existence
         */
        if (!$admin) {
            return $this->respondWithError(
                trans('validations/api/admin/auth/getTwoFactor.result.error.find')
            );
        }

        /**
         * Authorizing admin in gateway
         */
        $this->authMicroservice->retrieveAdmin(
            $request->input('email'),
            $request->input('password')
        );

        /**
         * Checking admin has initial password
         */
        if ($admin->isInitialPassword()) {
            return $this->respondWithError(
                trans('validations/api/admin/auth/getTwoFactor.result.error.initial')
            );
        }

        /**
         * Checking admin auth protection enabled
         */
        if ($admin->hasAuthProtection()) {
            return $this->respondWithError(
                trans('validations/api/admin/auth/getTwoFactor.result.error.twoFactor.enabled')
            );
        }

        /**
         * Generating Google 2FA secret key
         */
        $secretKey = $this->google2faService->getSecretKey();

        /**
         * Generating Google 2FA QR code
         */
        $qrCode = $this->google2faService->getQRCode(
            config('app.company_name'),
            $admin->email,
            $secretKey
        );

        return $this->respondWithSuccess(
            $this->transformItem([], new TwoFactorTransformer($secretKey, $qrCode)),
            trans('validations/api/admin/auth/getTwoFactor.result.success')
        );
    }

    /**
     * @param EnableTwoFactorRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function enableTwoFactor(
        EnableTwoFactorRequest $request
    ) : JsonResponse
    {
        /**
         * Getting user
         */
        $admin = $this->adminRepository->findByEmail(
            $request->input('email')
        );

        /**
         * Checking user existence
         */
        if (!$admin) {
            return $this->respondWithError(
                trans('validations/api/admin/auth/enableTwoFactor.result.error.find')
            );
        }

        /**
         * Authorizing admin in gateway
         */
        $this->authMicroservice->retrieveAdmin(
            $request->input('email'),
            $request->input('password')
        );

        /**
         * Checking admin has initial password
         */
        if ($admin->isInitialPassword()) {
            return $this->respondWithError(
                trans('validations/api/admin/auth/enableTwoFactor.result.error.initial')
            );
        }

        /**
         * Checking auth protection is enabled
         */
        if ($admin->hasAuthProtection()) {
            return $this->respondWithError(
                trans('validations/api/admin/auth/enableTwoFactor.result.error.twoFactor.enabled')
            );
        }

        /**
         * Creating admin auth protection
         */
        $this->adminAuthProtectionRepository->store(
            $admin,
            $request->input('secret_key')
        );

        return $this->respondWithSuccess(
            $this->transformItem($admin, new AdminTransformer),
            trans('validations/api/admin/auth/enableTwoFactor.result.success')
        );
    }

    /**
     * @param ExecuteTwoFactorRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function executeTwoFactor(
        ExecuteTwoFactorRequest $request
    ) : JsonResponse
    {
        /**
         * Getting user
         */
        $admin = $this->adminRepository->findByEmail(
            $request->input('email')
        );

        /**
         * Checking admin existence
         */
        if (!$admin) {
            return $this->respondWithError(
                trans('validations/api/admin/auth/executeTwoFactor.result.error.find')
            );
        }

        /**
         * Authorizing admin in gateway
         */
        $this->authMicroservice->retrieveAdmin(
            $request->input('email'),
            $request->input('password')
        );

        /**
         * Checking admin has initial password
         */
        if ($admin->isInitialPassword()) {
            return $this->respondWithError(
                trans('validations/api/admin/auth/executeTwoFactor.result.error.initial')
            );
        }

        /**
         * Checking auth protection is enabled
         */
        if (!$admin->hasAuthProtection()) {
            return $this->respondWithError(
                trans('validations/api/admin/auth/executeTwoFactor.result.error.twoFactor.enable')
            );
        }

        /**
         * Verifying auth protection
         */
        if (!$this->google2faService->verify(
            $admin->authProtection,
            $request->input('otp_password')
        )) {
            return $this->respondWithError(
                trans('validations/api/admin/auth/executeTwoFactor.result.error.otpPassword')
            );
        }

        /**
         * Getting token from gateway
         */
        $userResponse = $this->authMicroservice->loginUser(
            $request->input('email'),
            $request->input('password')
        );

        return $this->respondWithSuccess([
            'token' => $userResponse->token
        ], trans('validations/api/admin/auth/executeTwoFactor.result.success'));
    }

    /**
     * @param PasswordResetRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function passwordReset(
        PasswordResetRequest $request
    ) : JsonResponse
    {
        /**
         * Getting admin
         */
        $admin = $this->adminRepository->findByEmail(
            $request->input('email')
        );

        /**
         * Checking admin existence
         */
        if (!$admin) {
            return $this->respondWithError(
                trans('validations/api/admin/auth/passwordReset.result.error.find')
            );
        }

        /**
         * Updating admin
         */
        $admin = $this->adminRepository->setPasswordResetToken(
            $admin
        );

        /**
         * Sending admin password reset email notification
         */
        $this->emailNotificationService->sendAdminPasswordReset(
            $admin
        );

        return $this->respondWithSuccess(
            $this->transformItem($admin, new AdminTransformer),
            trans('validations/api/admin/auth/passwordReset.result.success')
        );
    }

    /**
     * @param PasswordSetupRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function passwordSetup(
        PasswordSetupRequest $request
    ) : JsonResponse
    {
        /**
         * Getting admin
         */
        $admin = $this->adminRepository->findByEmail(
            $request->input('email')
        );

        /**
         * Checking admin existence
         */
        if (!$admin) {
            return $this->respondWithError(
                trans('validations/api/admin/auth/passwordSetup.result.error.find')
            );
        }

        /**
         * Comparing password confirmation fields
         */
        if (!compareStrings(
            $request->input('password'),
            $request->input('password_confirm')
        )) {
            return $this->respondWithError(
                trans('validations/api/admin/auth/passwordSetup.result.error.mismatch.credentials')
            );
        }

        /**
         * Checking admin and token correlation
         */
        if ($admin->password_reset_token != $request->input('token')) {
            return $this->respondWithError(
                trans('validations/api/admin/auth/passwordSetup.result.error.mismatch.token')
            );
        }

        /**
         * Getting admin from gateway
         */
        $this->authMicroservice->updatePassword(
            $request->input('email'),
            $request->input('password')
        );

        /**
         * Updating an admin initial password flag
         */
        $admin = $this->adminRepository->updateInitialPassword(
            $admin,
            false
        );

        return $this->respondWithSuccess(
            $this->transformItem($admin, new AdminTransformer),
            trans('validations/api/admin/auth/passwordSetup.result.success')
        );
    }

    /**
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function getAuthAdmin() : JsonResponse
    {
        return $this->respondWithSuccess(
            $this->transformItem(
                AuthService::admin(),
                new AdminTransformer
            ), trans('validations/api/admin/auth/getAuthAdmin.result.success')
        );
    }

    /**
     * @return JsonResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function logout() : JsonResponse
    {
        /**
         * Logout admin from gateway
         */
        $this->authMicroservice->logout();

        return $this->respondWithSuccess([],
            trans('validations/api/admin/auth/logout.result.success')
        );
    }
}
