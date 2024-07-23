<?php

namespace App\Http\Controllers\Api\Guest\Auth\Interfaces;

use App\Http\Requests\Api\Guest\Auth\CheckEmailRequest;
use App\Http\Requests\Api\Guest\Auth\CheckUsernameRequest;
use App\Http\Requests\Api\Guest\Auth\InitializePasswordResetRequest;
use App\Http\Requests\Api\Guest\Auth\LoginRequest;
use App\Http\Requests\Api\Guest\Auth\RegisterEmailVerifyRequest;
use App\Http\Requests\Api\Guest\Auth\RegisterRequest;
use App\Http\Requests\Api\Guest\Auth\ResetPasswordRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface AuthControllerInterface
 *
 * @package App\Http\Controllers\Api\Guest\Auth\Interfaces
 */
interface AuthControllerInterface
{
    /**
     * This method provides getting data
     * by related entity repository with a certain query
     *
     * @return JsonResponse
     */
    public function getRegisterForm() : JsonResponse;

    /**
     * This method provides checking data
     * by related entity repository
     *
     * @param CheckUsernameRequest $request
     *
     * @return JsonResponse
     */
    public function checkUsername(
        CheckUsernameRequest $request
    ) : JsonResponse;

    /**
     * This method provides checking data
     * by related entity repository
     *
     * @param CheckEmailRequest $request
     *
     * @return JsonResponse
     */
    public function checkEmail(
        CheckEmailRequest $request
    ) : JsonResponse;

    /**
     * This method provides storing data
     * by related entity repository with a certain query
     *
     * @param RegisterRequest $request
     *
     * @return JsonResponse
     */
    public function register(
        RegisterRequest $request
    ) : JsonResponse;

    /**
     * This method provides action by related
     * entity repository with a certain query
     *
     * @param LoginRequest $request
     *
     * @return JsonResponse
     */
    public function login(
        LoginRequest $request
    ) : JsonResponse;

    /**
     * This method provides updating data
     * by related entity repository with a certain query
     *
     * @param RegisterEmailVerifyRequest $request
     *
     * @return JsonResponse
     */
    public function registerEmailVerify(
        RegisterEmailVerifyRequest $request
    ) : JsonResponse;

    /**
     * This method provides updating data
     * by related entity repository with a certain query
     *
     * @param InitializePasswordResetRequest $request
     *
     * @return JsonResponse
     */
    public function initializePasswordReset(
        InitializePasswordResetRequest $request
    ) : JsonResponse;

    /**
     * This method provides updating data
     * by related entity repository with a certain query
     *
     * @param ResetPasswordRequest $request
     *
     * @return JsonResponse
     */
    public function resetPassword(
        ResetPasswordRequest $request
    ) : JsonResponse;
}
