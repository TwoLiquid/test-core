<?php

namespace App\Http\Controllers\Api\General\Setting\Interfaces;

use App\Http\Requests\Api\General\Setting\Account\ChangeEmailRequest;
use App\Http\Requests\Api\General\Setting\Account\ChangeFastOrderRequest;
use App\Http\Requests\Api\General\Setting\Account\ChangePasswordRequest;
use App\Http\Requests\Api\General\Setting\Account\ChangeTimezoneRequest;
use App\Http\Requests\Api\General\Setting\Account\DeactivationRequest;
use App\Http\Requests\Api\General\Setting\Account\DeletionRequest;
use App\Http\Requests\Api\General\Setting\Account\GetBlockedUsersRequest;
use App\Http\Requests\Api\General\Setting\Account\UnsuspendRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface AccountSettingControllerInterface
 *
 * @package App\Http\Controllers\Api\General\Setting\Interfaces
 */
interface AccountSettingControllerInterface
{
    /**
     * This method provides getting data
     * by related service
     *
     * @return JsonResponse
     */
    public function index() : JsonResponse;

    /**
     * This method provides storing a single row
     * by related entity repository
     *
     * @param DeactivationRequest $request
     *
     * @return JsonResponse
     */
    public function deactivation(
        DeactivationRequest $request
    ) : JsonResponse;

    /**
     * This method provides updating single row
     * by related entity repository
     *
     * @return JsonResponse
     */
    public function closeDeactivation() : JsonResponse;

    /**
     * This method provides deleting single row
     * by related entity repository
     *
     * @return JsonResponse
     */
    public function cancelDeactivation() : JsonResponse;

    /**
     * This method provides storing a single row
     * by related entity repository
     *
     * @param DeletionRequest $request
     *
     * @return JsonResponse
     */
    public function deletion(
        DeletionRequest $request
    ) : JsonResponse;

    /**
     * This method provides updating single row
     * by related entity repository
     *
     * @return JsonResponse
     */
    public function closeDeletion() : JsonResponse;

    /**
     * This method provides deleting single row
     * by related entity repository
     *
     * @return JsonResponse
     */
    public function cancelDeletion() : JsonResponse;

    /**
     * This method provides storing a single row
     * by related entity repository
     *
     * @param UnsuspendRequest $request
     *
     * @return JsonResponse
     */
    public function unsuspend(
        UnsuspendRequest $request
    ) : JsonResponse;

    /**
     * This method provides updating single row
     * by related entity repository
     *
     * @return JsonResponse
     */
    public function closeUnsuspend() : JsonResponse;

    /**
     * This method provides deleting single row
     * by related entity repository
     *
     * @return JsonResponse
     */
    public function cancelUnsuspend() : JsonResponse;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param ChangeFastOrderRequest $request
     *
     * @return JsonResponse
     */
    public function changeFastOrder(
        ChangeFastOrderRequest $request
    ) : JsonResponse;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param ChangeEmailRequest $request
     *
     * @return JsonResponse
     */
    public function changeEmail(
        ChangeEmailRequest $request
    ) : JsonResponse;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @return JsonResponse
     */
    public function resubmitEmail() : JsonResponse;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param ChangePasswordRequest $request
     *
     * @return JsonResponse
     */
    public function changePassword(
        ChangePasswordRequest $request
    ) : JsonResponse;

    /**
     * This method provides getting data
     * by related entity repository
     *
     * @param GetBlockedUsersRequest $request
     *
     * @return JsonResponse
     */
    public function getBlockedUsers(
        GetBlockedUsersRequest $request
    ) : JsonResponse;

    /**
     * This method provides getting data
     * by related service
     *
     * @return JsonResponse
     */
    public function getFastOrderPage() : JsonResponse;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function changeLanguage(
        int $id
    ) : JsonResponse;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function changeCurrency(
        int $id
    ) : JsonResponse;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param ChangeTimezoneRequest $request
     *
     * @return JsonResponse
     */
    public function changeTimezone(
        ChangeTimezoneRequest $request
    ) : JsonResponse;

    /**
     * This method provides action by related
     * entity repository with a certain query
     *
     * @return JsonResponse
     */
    public function reactivateAccount() : JsonResponse;

    /**
     * This method provides action by related
     * entity repository with a certain query
     *
     * @return JsonResponse
     */
    public function restoreAccount() : JsonResponse;
}
