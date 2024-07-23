<?php

namespace App\Http\Controllers\Api\Admin\Vybe\Interfaces;

use App\Http\Requests\Api\Admin\Vybe\DeletionRequest\UpdateRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface VybeDeletionRequestControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\Vybe\Interfaces
 */
interface VybeDeletionRequestControllerInterface
{
    /**
     * This method provides getting a single row
     * by related entity repository
     *
     * @param string $id
     *
     * @return JsonResponse
     */
    public function show(
        string $id
    ) : JsonResponse;

    /**
     * This method provides updating single row
     * by related entity repository
     *
     * @param string $id
     * @param UpdateRequest $request
     *
     * @return JsonResponse
     */
    public function update(
        string $id,
        UpdateRequest $request
    ) : JsonResponse;
}
