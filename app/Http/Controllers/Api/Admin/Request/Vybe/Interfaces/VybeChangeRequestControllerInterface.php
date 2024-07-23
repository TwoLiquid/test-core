<?php

namespace App\Http\Controllers\Api\Admin\Request\Vybe\Interfaces;

use App\Http\Requests\Api\Admin\Request\Vybe\ChangeRequest\IndexRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface VybeChangeRequestControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\Request\Vybe\Interfaces
 */
interface VybeChangeRequestControllerInterface
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