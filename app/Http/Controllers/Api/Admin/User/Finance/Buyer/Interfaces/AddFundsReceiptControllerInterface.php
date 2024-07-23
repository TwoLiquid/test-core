<?php

namespace App\Http\Controllers\Api\Admin\User\Finance\Buyer\Interfaces;

use App\Http\Requests\Api\Admin\User\Finance\Buyer\AddFunds\Receipt\ExportRequest;
use App\Http\Requests\Api\Admin\User\Finance\Buyer\AddFunds\Receipt\IndexRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Interface AddFundsReceiptControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\User\Finance\Buyer\Interfaces
 */
interface AddFundsReceiptControllerInterface
{
    /**
     * This method provides getting all rows
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
     * This method provides getting all rows
     * by related entity repository
     *
     * @param int $id
     * @param string $type
     * @param ExportRequest $request
     *
     * @return BinaryFileResponse
     */
    public function export(
        int $id,
        string $type,
        ExportRequest $request
    ) : BinaryFileResponse;
}