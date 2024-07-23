<?php

namespace App\Http\Controllers\Api\Guest\Profile\Interfaces;

use App\Http\Requests\Api\Guest\Profile\Vybe\GetTimeslotUsersRequest;
use App\Http\Requests\Api\Guest\Profile\Vybe\ShowRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface VybeControllerInterface
 *
 * @package App\Http\Controllers\Api\Guest\Profile\Interfaces
 */
interface VybeControllerInterface
{
    /**
     * This method provides getting a single row
     * by related entity repository
     *
     * @param string $username
     * @param int $id
     * @param ShowRequest $request
     *
     * @return JsonResponse
     */
    public function show(
        string $username,
        int $id,
        ShowRequest $request
    ) : JsonResponse;

    /**
     * This method provides getting rows
     * by related entity repository
     *
     * @param int $id
     * @param GetTimeslotUsersRequest $request
     *
     * @return JsonResponse
     */
    public function getTimeslotUsers(
        int $id,
        GetTimeslotUsersRequest $request
    ) : JsonResponse;
}
