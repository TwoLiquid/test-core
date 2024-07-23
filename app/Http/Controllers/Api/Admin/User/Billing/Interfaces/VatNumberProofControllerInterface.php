<?php

namespace App\Http\Controllers\Api\Admin\User\Billing\Interfaces;

use App\Http\Requests\Api\Admin\User\Billing\VatNumberProof\UpdateExcludeTaxRequest;
use App\Http\Requests\Api\Admin\User\Billing\VatNumberProof\UpdateStatusRequest;
use App\Http\Requests\Api\Admin\User\Billing\VatNumberProof\UploadFilesRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface VatNumberProofControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\User\Billing\Interfaces
 */
interface VatNumberProofControllerInterface
{
    /**
     * This method provides uploading data
     * by related entity repository
     *
     * @param string $vatNumberProofId
     * @param UploadFilesRequest $request
     *
     * @return JsonResponse
     */
    public function uploadFiles(
        string $vatNumberProofId,
        UploadFilesRequest $request
    ) : JsonResponse;

    /**
     * This method provides updating row
     * by related entity repository
     *
     * @param string $vatNumberProofId
     * @param UpdateStatusRequest $request
     *
     * @return JsonResponse
     */
    public function updateStatus(
        string $vatNumberProofId,
        UpdateStatusRequest $request
    ) : JsonResponse;

    /**
     * This method provides updating row
     * by related entity repository
     *
     * @param string $vatNumberProofId
     * @param UpdateExcludeTaxRequest $request
     *
     * @return JsonResponse
     */
    public function updateExcludeTax(
        string $vatNumberProofId,
        UpdateExcludeTaxRequest $request
    ) : JsonResponse;
}