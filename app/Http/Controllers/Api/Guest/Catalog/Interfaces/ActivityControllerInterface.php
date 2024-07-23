<?php

namespace App\Http\Controllers\Api\Guest\Catalog\Interfaces;

use App\Http\Requests\Api\Guest\Catalog\Activity\GetByCodeRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface ActivityControllerInterface
 *
 * @package App\Http\Controllers\Api\Guest\Catalog\Interfaces
 */
interface ActivityControllerInterface
{
    /**
     * This method provides getting a single row
     * by related entity repository
     *
     * @param GetByCodeRequest $request
     *
     * @return JsonResponse
     */
    public function getByCode(
        GetByCodeRequest $request
    ) : JsonResponse;

    /**
     * This method provides getting a single row
     * by related entity repository
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function getRelatedActivity(
        int $id
    ) : JsonResponse;
}
