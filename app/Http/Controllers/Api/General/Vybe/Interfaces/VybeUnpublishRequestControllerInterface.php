<?php

namespace App\Http\Controllers\Api\General\Vybe\Interfaces;

use App\Http\Requests\Api\General\Vybe\UnpublishRequest\UpdateRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface VybeUnpublishRequestControllerInterface
 *
 * @package App\Http\Controllers\Api\General\Vybe\Interfaces
 */
interface VybeUnpublishRequestControllerInterface
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
     * This method provides deleting row
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
