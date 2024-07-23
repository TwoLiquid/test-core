<?php

namespace App\Http\Controllers\Api\Admin\General\IpBanList\Interfaces;

use App\Http\Requests\Api\Admin\General\IpBanList\DestroyManyRequest;
use App\Http\Requests\Api\Admin\General\IpBanList\IndexRequest;
use App\Http\Requests\Api\Admin\General\IpBanList\StoreRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface IpBanListControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\General\IpBanList\Interfaces
 */
interface IpBanListControllerInterface
{
    /**
     * This method provides getting all rows
     * by related entity repository
     *
     * @param IndexRequest $request
     *
     * @return JsonResponse
     */
    public function index(
        IndexRequest $request
    ) : JsonResponse;

    /**
     * This method provides getting a single row
     * by related entity repository
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show(
        int $id
    ) : JsonResponse;

    /**
     * This method provides storing a single row
     * by related entity repository
     *
     * @param StoreRequest $request
     *
     * @return JsonResponse
     */
    public function store(
        StoreRequest $request
    ) : JsonResponse;

    /**
     * This method provides deleting single row
     * by related entity repository
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function destroy(
        int $id
    ) : JsonResponse;

    /**
     * This method provides deleting rows
     * by related entity repository
     *
     * @param DestroyManyRequest $request
     *
     * @return JsonResponse
     */
    public function destroyMany(
        DestroyManyRequest $request
    ) : JsonResponse;
}
