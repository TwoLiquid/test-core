<?php

namespace App\Http\Controllers\Api\Admin\Vybe\Interfaces;

use Illuminate\Http\JsonResponse;

/**
 * Interface VybeVersionControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\Vybe\Interfaces
 */
interface VybeVersionControllerInterface
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
}
