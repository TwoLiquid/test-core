<?php

namespace App\Http\Controllers\Api\General\Home\Interfaces;

use App\Http\Requests\Api\General\Home\Vybe\GetOrderedByAuthUserVybesRequest;
use App\Http\Requests\Api\General\Home\Vybe\GetVybesByFollowingUsersRequest;
use App\Http\Requests\Api\General\Home\Vybe\GetVybesNotDiscoveredRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface VybeControllerInterface
 *
 * @package App\Http\Controllers\Api\General\Home\Interfaces
 */
interface VybeControllerInterface
{   
    /**
     * This method provides getting multiple rows
     * by related entity repository
     *
     * @param GetOrderedByAuthUserVybesRequest $request
     * 
     * @return JsonResponse
     */
    public function getOrderedByAuthUserVybes(
        GetOrderedByAuthUserVybesRequest $request
    ) : JsonResponse;

    /**
     * This method provides getting multiple rows
     * by related entity repository
     *
     * @param GetVybesByFollowingUsersRequest $request
     * 
     * @return JsonResponse
     */
    public function getVybesByFollowingUsers(
        GetVybesByFollowingUsersRequest $request
    ) : JsonResponse;

    /**
     * This method provides getting multiple rows
     * by related entity repository
     *
     * @param GetVybesNotDiscoveredRequest $request
     * 
     * @return JsonResponse
     */
    public function getVybesNotDiscovered(
        GetVybesNotDiscoveredRequest $request
    ) : JsonResponse;
}