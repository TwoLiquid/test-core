<?php

namespace App\Http\Controllers\Api\Guest\Home\Interfaces;

use Illuminate\Http\JsonResponse;

/**
 * Interface UserControllerInterface
 *
 * @package App\Http\Controllers\Api\Guest\Home\Interfaces
 */
interface UserControllerInterface
{
    /**
     * This method provides getting multiple rows
     * with an eloquent model
     *
     * @return JsonResponse
     */
    public function index() : JsonResponse;
}
