<?php

namespace App\Http\Controllers\Api\Admin\Request\Vybe\Interfaces;

use App\Http\Requests\Api\Admin\Request\Vybe\DeletionRequest\IndexRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface VybeDeletionRequestControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\Request\Vybe\Interfaces
 */
interface VybeDeletionRequestControllerInterface
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
}