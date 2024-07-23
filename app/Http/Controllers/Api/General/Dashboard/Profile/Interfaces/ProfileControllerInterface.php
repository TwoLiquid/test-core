<?php

namespace App\Http\Controllers\Api\General\Dashboard\Profile\Interfaces;

use App\Http\Requests\Api\General\Dashboard\Profile\CheckEmailRequest;
use App\Http\Requests\Api\General\Dashboard\Profile\CheckUsernameRequest;
use App\Http\Requests\Api\General\Dashboard\Profile\UpdateRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface ProfileControllerInterface
 *
 * @package App\Http\Controllers\Api\General\Dashboard\Profile\Interfaces
 */
interface ProfileControllerInterface
{
    /**
     * This method provides checking single row existence
     * by related entity repository
     *
     * @param CheckEmailRequest $request
     *
     * @return JsonResponse
     */
    public function checkEmail(
        CheckEmailRequest $request
    ) : JsonResponse;

    /**
     * This method provides checking single row existence
     * by related entity repository
     *
     * @param CheckUsernameRequest $request
     *
     * @return JsonResponse
     */
    public function checkUsername(
        CheckUsernameRequest $request
    ) : JsonResponse;

    /**
     * This method provides updating data
     *
     * @param UpdateRequest $request
     *
     * @return JsonResponse
     */
    public function update(
        UpdateRequest $request
    ) : JsonResponse;

    /**
     * @return JsonResponse
     */
    public function close() : JsonResponse;

    /**
     * This method provides updating data
     *
     * @return JsonResponse
     */
    public function cancel() : JsonResponse;
}