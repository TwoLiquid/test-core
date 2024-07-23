<?php

namespace App\Http\Controllers\Api\General\PersonalityTrait\Interfaces;

use Illuminate\Http\JsonResponse;

/**
 * Interface PersonalityTraitVoteControllerInterface
 *
 * @package App\Http\Controllers\Api\General\PersonalityTrait\Interfaces
 */
interface PersonalityTraitVoteControllerInterface
{
    /**
     * This method provides storing a single row
     * by related entity repository
     *
     * @param int $id
     * @param int $userId
     *
     * @return JsonResponse
     */
    public function store(
        int $id,
        int $userId
    ) : JsonResponse;

    /**
     * This method provides deleting single row
     * by related entity repository
     *
     * @param int $id
     * @param int $userId
     *
     * @return JsonResponse
     */
    public function destroy(
        int $id,
        int $userId
    ) : JsonResponse;
}
