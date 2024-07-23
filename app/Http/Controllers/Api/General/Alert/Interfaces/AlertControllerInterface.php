<?php

namespace App\Http\Controllers\Api\General\Alert\Interfaces;

use App\Http\Requests\Api\General\Alert\UpdateRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface AlertControllerInterface
 *
 * @package App\Http\Controllers\Api\General\Alert\Interfaces
 */
interface AlertControllerInterface
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
     * @param UpdateRequest $request
     *
     * @return JsonResponse
     */
    public function update(
        int $id,
        UpdateRequest $request
    ) : JsonResponse;

//    /**
//     * This method provides deleting single row data
//     * by related entity repository
//     *
//     * @param int $id
//     * @param int $imageId
//     *
//     * @return JsonResponse
//     */
//    public function deleteImage(
//        int $id,
//        int $imageId
//    ) : JsonResponse;

//    /**
//     * This method provides deleting single row data
//     * by related entity repository
//     *
//     * @param int $id
//     * @param int $soundId
//     *
//     * @return JsonResponse
//     */
//    public function deleteSound(
//        int $id,
//        int $soundId
//    ) : JsonResponse;
}