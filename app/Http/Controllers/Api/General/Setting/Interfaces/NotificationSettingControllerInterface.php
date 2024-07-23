<?php

namespace App\Http\Controllers\Api\General\Setting\Interfaces;

use App\Http\Requests\Api\General\Setting\Notification\SaveChangesRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface NotificationSettingControllerInterface
 *
 * @package App\Http\Controllers\Api\General\Setting\Interfaces
 */
interface NotificationSettingControllerInterface
{
    /**
     * This method provides getting rows
     * by related entity repository
     *
     * @return JsonResponse
     */
    public function index() : JsonResponse;

    /**
     * This method provides storing a single row
     * by related entity repository
     *
     * @param SaveChangesRequest $request
     *
     * @return JsonResponse
     */
    public function saveChanges(
        SaveChangesRequest $request
    ) : JsonResponse;
}
