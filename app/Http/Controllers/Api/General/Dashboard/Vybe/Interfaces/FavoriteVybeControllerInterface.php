<?php

namespace App\Http\Controllers\Api\General\Dashboard\Vybe\Interfaces;

use App\Http\Requests\Api\General\Dashboard\Vybe\Favorite\GetMoreFavoriteVybesRequest;
use App\Http\Requests\Api\General\Dashboard\Vybe\Favorite\IndexRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface FavoriteVybeControllerInterface
 *
 * @package App\Http\Controllers\Api\General\Dashboard\Vybe\Interfaces
 */
interface FavoriteVybeControllerInterface
{
    /**
     * This method provides getting all rows
     * by related entity repository
     *
     * @param IndexRequest $request
     *
     * @return JsonResponse
     */
    public function index(
        IndexRequest $request
    ) : JsonResponse;

    /**
     * This method provides getting rows
     * by related entity repository
     *
     * @param GetMoreFavoriteVybesRequest $request
     *
     * @return JsonResponse
     */
    public function getMoreFavoriteVybes(
        GetMoreFavoriteVybesRequest $request
    ) : JsonResponse;
}