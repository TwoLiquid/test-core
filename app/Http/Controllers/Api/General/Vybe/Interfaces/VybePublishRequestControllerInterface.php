<?php

namespace App\Http\Controllers\Api\General\Vybe\Interfaces;

use App\Http\Requests\Api\General\Vybe\PublishRequest\UpdateRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface VybePublishRequestControllerInterface
 *
 * @package App\Http\Controllers\Api\General\Vybe\Interfaces
 */
interface VybePublishRequestControllerInterface
{
    /**
     * This method provides updating single row
     * by related entity repository with a certain query
     *
     * @param int $id
     * @param UpdateRequest $request
     *
     * @return JsonResponse
     */
    public function update(
        int $id,
        UpdateRequest $request
    ) : JsonResponse;

    /**
     * This method provides updating row
     * by related entity repository
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function close(
        int $id
    ) : JsonResponse;

    /**
     * This method provides deleting rows
     * by related entity repository
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function cancel(
        int $id
    ) : JsonResponse;
}
