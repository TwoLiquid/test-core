<?php

namespace App\Http\Controllers\Api\General\Dashboard\Purchase\Interfaces;

use App\Http\Requests\Api\General\Dashboard\Purchase\IndexRequest;
use App\Http\Requests\Api\General\Dashboard\Purchase\RescheduleRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface PurchaseControllerInterface
 *
 * @package App\Http\Controllers\Api\General\Dashboard\Purchase\Interfaces
 */
interface PurchaseControllerInterface
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

//    /**
//     * This method provides updating rows
//     * by related entity repository
//     *
//     * @param int $id
//     *
//     * @return JsonResponse
//     */
//    public function makePayment(
//        int $id
//    ) : JsonResponse;

//    /**
//     * This method provides updating single row
//     * by related entity repository
//     *
//     * @param int $id
//     *
//     * @return JsonResponse
//     */
//    public function cancelPurchase(
//        int $id
//    ) : JsonResponse;

    /**
     * This method provides updating single row
     * by related entity repository
     *
     * @param int $id
     * @param RescheduleRequest $request
     *
     * @return JsonResponse
     */
    public function reschedule(
        int $id,
        RescheduleRequest $request
    ) : JsonResponse;

    /**
     * This method provides updating single row
     * by related entity repository
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function acceptRescheduleRequest(
        int $id
    ) : JsonResponse;

    /**
     * This method provides updating single row
     * by related entity repository
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function declineRescheduleRequest(
        int $id
    ) : JsonResponse;

    /**
     * This method provides updating single row
     * by related entity repository
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function cancelRescheduleRequest(
        int $id
    ) : JsonResponse;

    /**
     * This method provides updating single row
     * by related entity repository
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function cancelOrder(
        int $id
    ) : JsonResponse;

    /**
     * This method provides updating single row
     * by related entity repository
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function markAsFinished(
        int $id
    ) : JsonResponse;

//    /**
//     * This method provides updating single row
//     * by related entity repository
//     *
//     * @param int $id
//     *
//     * @return JsonResponse
//     */
//    public function openDispute(
//        int $id
//    ) : JsonResponse;

    /**
     * This method provides updating single row
     * by related entity repository
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function acceptFinishRequest(
        int $id
    ) : JsonResponse;

    /**
     * This method provides updating single row
     * by related entity repository
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function declineFinishRequest(
        int $id
    ) : JsonResponse;
}