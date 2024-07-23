<?php

namespace App\Http\Controllers\Api\General\Navbar\Interfaces;

use Illuminate\Http\JsonResponse;

/**
 * Interface NavbarControllerInterface
 *
 * @package App\Http\Controllers\Api\General\Navbar\Interfaces
 */
interface NavbarControllerInterface
{
    /**
     * This method provides getting all rows
     * by related entity repository
     *
     * @return JsonResponse
     */
    public function index() : JsonResponse;

    /**
     * This method provides updating single row
     * by related entity repository
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function updateLanguage(
        int $id
    ) : JsonResponse;

    /**
     * This method provides updating single row
     * by related entity repository
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function updateCurrency(
        int $id
    ) : JsonResponse;

    /**
     * This method provides updating single row
     * by related entity repository
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function updateStateStatus(
        int $id
    ) : JsonResponse;

    /**
     * This method provides updating single row
     * by related entity repository
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function updateTimezone(
        int $id
    ) : JsonResponse;

    /**
     * This method provides updating single row
     * by related entity repository
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function updateTheme(
        int $id
    ) : JsonResponse;
}
