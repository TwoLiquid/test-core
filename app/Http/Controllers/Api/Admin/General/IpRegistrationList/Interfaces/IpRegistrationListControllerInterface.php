<?php

namespace App\Http\Controllers\Api\Admin\General\IpRegistrationList\Interfaces;

use App\Http\Requests\Api\Admin\General\IpRegistrationList\IndexRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface IpRegistrationListControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\General\IpRegistrationList\Interfaces
 */
interface IpRegistrationListControllerInterface
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