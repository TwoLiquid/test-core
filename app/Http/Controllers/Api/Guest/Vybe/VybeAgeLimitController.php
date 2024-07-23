<?php

namespace App\Http\Controllers\Api\Guest\Vybe;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Vybe\Interfaces\VybeAgeLimitControllerInterface;
use App\Lists\Vybe\AgeLimit\VybeAgeLimitList;
use App\Transformers\Api\Guest\Vybe\AgeLimit\VybeAgeLimitTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class VybeAgeLimitController
 *
 * @package App\Http\Controllers\Api\Guest\Vybe
 */
final class VybeAgeLimitController extends BaseController implements VybeAgeLimitControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting vybe age limits
         */
        $vybeAgeLimitListItems = VybeAgeLimitList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($vybeAgeLimitListItems, new VybeAgeLimitTransformer),
            trans('validations/api/guest/vybe/ageLimit/index.result.success')
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
         * Getting vybe age limit
         */
        $vybeAgeLimitListItem = VybeAgeLimitList::getItem($id);

        /**
         * Checking vybe age limit existence
         */
        if (!$vybeAgeLimitListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/vybe/ageLimit/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($vybeAgeLimitListItem, new VybeAgeLimitTransformer),
            trans('validations/api/guest/vybe/ageLimit/show.result.success')
        );
    }
}
