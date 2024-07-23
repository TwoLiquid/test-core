<?php

namespace App\Http\Controllers\Api\Admin\Order\Interfaces;

use App\Http\Requests\Api\Admin\Order\OrderItem\ExportRequest;
use App\Http\Requests\Api\Admin\Order\OrderItem\IndexRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Interface OrderItemControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\Order\Interfaces
 */
interface OrderItemControllerInterface
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
     * This method provides downloading file
     * by related entity repository
     *
     * @param string $type
     * @param ExportRequest $request
     *
     * @return BinaryFileResponse
     */
    public function export(
        string $type,
        ExportRequest $request
    ) : BinaryFileResponse;
}
