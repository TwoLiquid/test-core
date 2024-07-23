<?php

namespace App\Http\Controllers\Api\Guest\Language\Interfaces;

use Illuminate\Http\JsonResponse;
use App\Http\Requests\Api\Guest\Language\IndexRequest;

/**
 * Interface LanguageControllerInterface
 *
 * @package App\Http\Controllers\Api\Guest\Language\Interfaces
 */
interface LanguageControllerInterface
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
     * This method provides getting a single row
     * by related entity repository
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show(
        int $id
    ) : JsonResponse;
}
