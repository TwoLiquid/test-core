<?php

namespace App\Http\Controllers\Api\Guest\VatNumberProof;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\VatNumberProof\Interfaces\VatNumberProofStatusControllerInterface;
use App\Lists\VatNumberProof\Status\VatNumberProofStatusList;
use App\Transformers\Api\Guest\VatNumberProof\Status\VatNumberProofStatusTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class VatNumberProofStatusController
 *
 * @package App\Http\Controllers\Api\Guest\VatNumberProof
 */
final class VatNumberProofStatusController extends BaseController implements VatNumberProofStatusControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting vat number proof statuses
         */
        $vatNumberProofStatusListItems = VatNumberProofStatusList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($vatNumberProofStatusListItems, new VatNumberProofStatusTransformer),
            trans('validations/api/guest/vatNumberProof/status/index.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting vat number proof status
         */
        $vatNumberProofStatusListItem = VatNumberProofStatusList::getItem($id);

        /**
         * Checking vat number proof status existence
         */
        if (!$vatNumberProofStatusListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/vatNumberProof/status/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($vatNumberProofStatusListItem, new VatNumberProofStatusTransformer),
            trans('validations/api/guest/vatNumberProof/status/show.result.success')
        );
    }
}
