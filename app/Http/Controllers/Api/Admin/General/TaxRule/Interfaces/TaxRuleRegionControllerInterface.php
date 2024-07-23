<?php

namespace App\Http\Controllers\Api\Admin\General\TaxRule\Interfaces;

use App\Http\Requests\Api\Admin\General\TaxRule\Region\IndexRequest;
use App\Http\Requests\Api\Admin\General\TaxRule\Region\StoreRequest;
use App\Http\Requests\Api\Admin\General\TaxRule\Region\UpdateRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface TaxRuleRegionControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\General\TaxRule\Interfaces
 */
interface TaxRuleRegionControllerInterface
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
}
