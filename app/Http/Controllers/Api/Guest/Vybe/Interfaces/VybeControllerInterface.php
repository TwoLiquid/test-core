<?php

namespace App\Http\Controllers\Api\Guest\Vybe\Interfaces;

use App\Http\Requests\Api\Guest\Vybe\GetCalendarRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface VybeControllerInterface
 *
 * @package App\Http\Controllers\Api\Guest\Vybe\Interfaces
 */
interface VybeControllerInterface
{
    /**
     * This method provides getting all rows
     * by related entity repository
     *
     * @param int $id
     * @param GetCalendarRequest $request
     *
     * @return JsonResponse
     */
    public function getCalendar(
        int $id,
        GetCalendarRequest $request
    ) : JsonResponse;
}
