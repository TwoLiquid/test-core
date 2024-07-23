<?php

namespace App\Http\Controllers\Api\General\Vybe\Interfaces;

use App\Http\Requests\Api\General\Vybe\Step\StoreChangesRequest;
use App\Http\Requests\Api\General\Vybe\Step\StoreNextRequest;
use App\Http\Requests\Api\General\Vybe\Step\UpdateFifthStepChangesRequest;
use App\Http\Requests\Api\General\Vybe\Step\UpdateFifthStepNext;
use App\Http\Requests\Api\General\Vybe\Step\UpdateFirstStepChangesRequest;
use App\Http\Requests\Api\General\Vybe\Step\UpdateFirstStepNextRequest;
use App\Http\Requests\Api\General\Vybe\Step\UpdateFourthStepChangesRequest;
use App\Http\Requests\Api\General\Vybe\Step\UpdateFourthStepNextRequest;
use App\Http\Requests\Api\General\Vybe\Step\UpdateSecondStepChangesRequest;
use App\Http\Requests\Api\General\Vybe\Step\UpdateSecondStepNextRequest;
use App\Http\Requests\Api\General\Vybe\Step\UpdateThirdStepChangesRequest;
use App\Http\Requests\Api\General\Vybe\Step\UpdateThirdStepNextRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface DraftVybeControllerInterface
 *
 * @package App\Http\Controllers\Api\General\Vybe\Interfaces
 */
interface VybeStepControllerInterface
{
    /**
     * This method provides storing data
     * by related repository
     *
     * @param StoreChangesRequest $request
     *
     * @return JsonResponse
     */
    public function storeChanges(
        StoreChangesRequest $request
    ) : JsonResponse;

    /**
     * This method provides storing data
     * by related repository
     *
     * @param StoreNextRequest $request
     *
     * @return JsonResponse
     */
    public function storeNext(
        StoreNextRequest $request
    ) : JsonResponse;

    /**
     * This method provides updating data
     * by related repository
     *
     * @param int $id
     * @param UpdateFirstStepChangesRequest $request
     *
     * @return JsonResponse
     */
    public function updateFirstStepChanges(
        int $id,
        UpdateFirstStepChangesRequest $request
    ) : JsonResponse;

    /**
     * This method provides updating data
     * by related repository
     *
     * @param int $id
     * @param UpdateFirstStepNextRequest $request
     *
     * @return JsonResponse
     */
    public function updateFirstStepNext(
        int $id,
        UpdateFirstStepNextRequest $request
    ) : JsonResponse;

    /**
     * This method provides updating data
     * by related repository
     *
     * @param int $id
     * @param UpdateSecondStepChangesRequest $request
     *
     * @return JsonResponse
     */
    public function updateSecondStepChanges(
        int $id,
        UpdateSecondStepChangesRequest $request
    ) : JsonResponse;

    /**
     * This method provides updating data
     * by related repository
     *
     * @param int $id
     * @param UpdateSecondStepNextRequest $request
     *
     * @return JsonResponse
     */
    public function updateSecondStepNext(
        int $id,
        UpdateSecondStepNextRequest $request
    ) : JsonResponse;

    /**
     * This method provides updating data
     * by related repository
     *
     * @param int $id
     * @param UpdateThirdStepChangesRequest $request
     *
     * @return JsonResponse
     */
    public function updateThirdStepChanges(
        int $id,
        UpdateThirdStepChangesRequest $request
    ) : JsonResponse;

    /**
     * This method provides updating data
     * by related repository
     *
     * @param int $id
     * @param UpdateThirdStepNextRequest $request
     *
     * @return JsonResponse
     */
    public function updateThirdStepNext(
        int $id,
        UpdateThirdStepNextRequest $request
    ) : JsonResponse;

    /**
     * This method provides updating data
     * by related repository
     *
     * @param int $id
     * @param UpdateFourthStepChangesRequest $request
     *
     * @return JsonResponse
     */
    public function updateFourthStepChanges(
        int $id,
        UpdateFourthStepChangesRequest $request
    ) : JsonResponse;

    /**
     * This method provides updating data
     * by related repository
     *
     * @param int $id
     * @param UpdateFourthStepNextRequest $request
     *
     * @return JsonResponse
     */
    public function updateFourthStepNext(
        int $id,
        UpdateFourthStepNextRequest $request
    ) : JsonResponse;

    /**
     * This method provides updating data
     * by related repository
     *
     * @param int $id
     * @param UpdateFifthStepChangesRequest $request
     *
     * @return JsonResponse
     */
    public function updateFifthStepChanges(
        int $id,
        UpdateFifthStepChangesRequest $request
    ) : JsonResponse;

    /**
     * This method provides updating data
     * by related repository
     *
     * @param int $id
     * @param UpdateFifthStepNext $request
     *
     * @return JsonResponse
     */
    public function updateFifthStepNext(
        int $id,
        UpdateFifthStepNext $request
    ) : JsonResponse;
}