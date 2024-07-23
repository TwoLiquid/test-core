<?php

namespace App\Http\Controllers\Api\Admin\User\Note\Interfaces;

use App\Http\Requests\Api\Admin\User\Note\IndexRequest;
use App\Http\Requests\Api\Admin\User\Note\StoreRequest;
use App\Http\Requests\Api\Admin\User\Note\UpdateRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface UserNoteControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\User\Note\Interfaces
 */
interface UserNoteControllerInterface
{
    /**
     * This method provides getting rows
     * by related entity repository
     *
     * @param int $id
     * @param IndexRequest $request
     *
     * @return JsonResponse
     */
    public function index(
        int $id,
        IndexRequest $request
    ) : JsonResponse;

    /**
     * This method provides storing data
     * by related entity repository
     *
     * @param int $id
     * @param StoreRequest $request
     *
     * @return JsonResponse
     */
    public function store(
        int $id,
        StoreRequest $request
    ) : JsonResponse;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param int $id
     * @param int $userNoteId
     * @param UpdateRequest $request
     *
     * @return JsonResponse
     */
    public function update(
        int $id,
        int $userNoteId,
        UpdateRequest $request
    ) : JsonResponse;

    /**
     * This method provides deleting data
     * by related entity repository
     *
     * @param int $id
     * @param int $userNoteId
     *
     * @return JsonResponse
     */
    public function destroy(
        int $id,
        int $userNoteId
    ) : JsonResponse;
}
