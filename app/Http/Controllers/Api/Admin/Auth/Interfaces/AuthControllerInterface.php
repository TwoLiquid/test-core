<?php

namespace App\Http\Controllers\Api\Admin\Auth\Interfaces;

use App\Http\Requests\Api\Admin\Auth\EnableTwoFactorRequest;
use App\Http\Requests\Api\Admin\Auth\ExecuteTwoFactorRequest;
use App\Http\Requests\Api\Admin\Auth\GetTwoFactorRequest;
use App\Http\Requests\Api\Admin\Auth\LoginRequest;
use App\Http\Requests\Api\Admin\Auth\PasswordResetRequest;
use App\Http\Requests\Api\Admin\Auth\PasswordSetupRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface AuthControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\Auth\Interfaces
 */
interface AuthControllerInterface
{
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
     * This method provides getting data
     * by related entity repository
     *
     * @param GetTwoFactorRequest $request
     *
     * @return JsonResponse
     */
    public function getTwoFactor(
        GetTwoFactorRequest $request
    ) : JsonResponse;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param EnableTwoFactorRequest $request
     *
     * @return JsonResponse
     */
    public function enableTwoFactor(
        EnableTwoFactorRequest $request
    ) : JsonResponse;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param ExecuteTwoFactorRequest $request
     *
     * @return JsonResponse
     */
    public function executeTwoFactor(
        ExecuteTwoFactorRequest $request
    ) : JsonResponse;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param PasswordResetRequest $request
     *
     * @return JsonResponse
     */
    public function passwordReset(
        PasswordResetRequest $request
    ) : JsonResponse;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param PasswordSetupRequest $request
     *
     * @return JsonResponse
     */
    public function passwordSetup(
        PasswordSetupRequest $request
    ) : JsonResponse;

    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return JsonResponse
     */
    public function getAuthAdmin() : JsonResponse;

    /**
     * This method provides action by related
     * entity repository with a certain query
     *
     * @return JsonResponse
     */
    public function logout() : JsonResponse;
}
