<?php

namespace App\Http\Controllers\Api\Guest\Navbar\Interfaces;

use Illuminate\Http\JsonResponse;

/**
 * Interface NavbarControllerInterface
 *
 * @package App\Http\Controllers\Api\Guest\Navbar\Interfaces
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
}