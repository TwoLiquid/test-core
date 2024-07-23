<?php

namespace App\Http\Controllers\Api\General\Dashboard\Finance\Seller\Interfaces;

use App\Http\Requests\Api\General\Dashboard\Finance\Seller\Invoice\Tip\IndexRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface TipInvoiceForSellerControllerInterface
 *
 * @package App\Http\Controllers\Api\General\Dashboard\Finance\Seller\Interfaces
 */
interface TipInvoiceForSellerControllerInterface
{
    /**
     * This method provides getting rows
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
