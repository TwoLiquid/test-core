<?php

namespace App\Http\Controllers\Api\Guest\Search\Interfaces;

use App\Http\Requests\Api\Guest\Search\Vybe\IndexRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface VybeControllerInterface
 *
 * @package App\Http\Controllers\Api\Guest\Search\Interfaces
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
}