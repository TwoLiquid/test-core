<?php

namespace App\Http\Controllers\Api\Admin\Order\Interfaces;

use App\Http\Requests\Api\Admin\Order\Tip\ExportRequest;
use App\Http\Requests\Api\Admin\Order\Tip\IndexRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Interface TipControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\Order\Interfaces
 */
interface TipControllerInterface
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