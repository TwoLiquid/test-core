<?php

namespace App\Http\Controllers\Api\General\Profile\Interfaces;

use Illuminate\Http\JsonResponse;

/**
 * Interface HomeControllerInterface
 *
 * @package App\Http\Controllers\Api\General\Profile\Interfaces
 */
interface HomeControllerInterface
{
    /**
     * This method provides updating single row
     * by related entity repository
     *
     * @param string $username
     * @param int $id
     *
     * @return JsonResponse
     */
    public function personalityTraitVote(
        string $username,
        int $id
    ) : JsonResponse;

    /**
     * This method provides updating single row
     * by related entity repository
     *
     * @param string $username
     * @param int $imageId
     *
     * @return JsonResponse
     */
    public function likeUserImage(
        string $username,
        int $imageId
    ) : JsonResponse;

    /**
     * This method provides updating single row
     * by related entity repository
     *
     * @param string $username
     * @param int $videoId
     *
     * @return JsonResponse
     */
    public function likeUserVideo(
        string $username,
        int $videoId
    ) : JsonResponse;
}