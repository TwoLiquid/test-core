<?php

namespace App\Http\Controllers\Api\Guest\Profile\Interfaces;

use App\Http\Requests\Api\Guest\Profile\Home\GetFavoriteVybesRequest;
use App\Http\Requests\Api\Guest\Profile\Home\GetSubscribersRequest;
use App\Http\Requests\Api\Guest\Profile\Home\GetSubscriptionsRequest;
use App\Http\Requests\Api\Guest\Profile\Home\GetVybesRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface HomeControllerInterface
 *
 * @package App\Http\Controllers\Api\Guest\Profile\Interfaces
 */
interface HomeControllerInterface
{
    /**
     * This method provides getting rows
     * by related entity repository
     *
     * @param string $username
     *
     * @return JsonResponse
     */
    public function index(
        string $username
    ) : JsonResponse;

    /**
     * This method provides getting rows
     * by related entity repository
     *
     * @param string $username
     * @param GetVybesRequest $request
     *
     * @return JsonResponse
     */
    public function getVybes(
        string $username,
        GetVybesRequest $request
    ) : JsonResponse;

    /**
     * This method provides getting rows
     * by related entity repository
     *
     * @param string $username
     * @param GetFavoriteVybesRequest $request
     *
     * @return JsonResponse
     */
    public function getFavoriteVybes(
        string $username,
        GetFavoriteVybesRequest $request
    ) : JsonResponse;

    /**
     * This method provides getting rows
     * by related entity repository
     *
     * @param string $username
     * @param GetSubscriptionsRequest $request
     *
     * @return JsonResponse
     */
    public function getSubscriptions(
        string $username,
        GetSubscriptionsRequest $request
    ) : JsonResponse;

    /**
     * This method provides getting rows
     * by related entity repository
     *
     * @param string $username
     * @param GetSubscribersRequest $request
     *
     * @return JsonResponse
     */
    public function getSubscribers(
        string $username,
        GetSubscribersRequest $request
    ) : JsonResponse;
}