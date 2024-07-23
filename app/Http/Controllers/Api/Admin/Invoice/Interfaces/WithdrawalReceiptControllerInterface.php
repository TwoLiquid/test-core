<?php

namespace App\Http\Controllers\Api\Admin\Invoice\Interfaces;

use App\Http\Requests\Api\Admin\Invoice\Withdrawal\Receipt\ExportRequest;
use App\Http\Requests\Api\Admin\Invoice\Withdrawal\Receipt\IndexRequest;
use App\Http\Requests\Api\Admin\Invoice\Withdrawal\Receipt\AddTransferRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface WithdrawalReceiptControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\Invoice\Interfaces
 */
interface WithdrawalReceiptControllerInterface
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
     * This method provides getting row
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
     * This method provides creating a single row
     * by related entity repository
     *
     * @param int $id
     * @param AddTransferRequest $request
     *
     * @return JsonResponse
     */
    public function addTransfer(
        int $id,
        AddTransferRequest $request
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

    /**
     * This method provides getting a single row
     * by related entity repository
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function viewPdf(
        int $id
    ) : JsonResponse;

    /**
     * This method provides getting file
     * by related entity repository
     *
     * @param int $id
     *
     * @return Response
     */
    public function downloadPdf(
        int $id
    ) : Response;
}
