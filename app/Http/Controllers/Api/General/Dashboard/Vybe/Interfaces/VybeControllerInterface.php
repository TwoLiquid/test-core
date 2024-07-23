<?php

namespace App\Http\Controllers\Api\General\Dashboard\Vybe\Interfaces;

use App\Http\Requests\Api\General\Dashboard\Vybe\GetMoreVybesRequest;
use App\Http\Requests\Api\General\Dashboard\Vybe\IndexRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface VybeControllerInterface
 *
 * @package App\Http\Controllers\Api\General\Dashboard\Vybe\Interfaces
 */
interface VybeControllerInterface
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
     * @param GetMoreVybesRequest $request
     *
     * @return JsonResponse
     */
    public function getMoreVybes(
        GetMoreVybesRequest $request
    ) : JsonResponse;

    /**
     * This method provides deleting rows
     * by related entity repository
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function deleteUncompleted(
        int $id
    ) : JsonResponse;
}