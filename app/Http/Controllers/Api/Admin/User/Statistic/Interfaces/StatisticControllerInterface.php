<?php

namespace App\Http\Controllers\Api\Admin\User\Statistic\Interfaces;

use Illuminate\Http\JsonResponse;

/**
 * Interface StatisticControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\User\Statistic\Interfaces
 */
interface StatisticControllerInterface
{
    /**
     * This method provides getting a single row
     * by related entity repository
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function index(
        int $id
    ) : JsonResponse;
}
