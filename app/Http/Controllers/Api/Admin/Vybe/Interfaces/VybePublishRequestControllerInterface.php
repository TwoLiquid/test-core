<?php

namespace App\Http\Controllers\Api\Admin\Vybe\Interfaces;

use App\Http\Requests\Api\Admin\Vybe\PublishRequest\UpdateRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface VybePublishRequestControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\Vybe\Interfaces
 */
interface VybePublishRequestControllerInterface
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
