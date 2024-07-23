<?php

namespace App\Http\Controllers\Api\Admin\Invoice\Interfaces;

use App\Http\Requests\Api\Admin\Invoice\Tip\Seller\ExportRequest;
use App\Http\Requests\Api\Admin\Invoice\Tip\Seller\IndexRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface TipInvoiceSellerControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\Invoice\Interfaces
 */
interface TipInvoiceSellerControllerInterface
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