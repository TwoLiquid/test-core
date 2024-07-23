<?php

namespace App\Http\Controllers\Api\Guest\Vybe;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Vybe\Interfaces\VybeTypeControllerInterface;
use App\Lists\Vybe\Type\VybeTypeList;
use App\Transformers\Api\Guest\Vybe\Type\VybeTypeTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class ServiceTypeController
 *
 * @package App\Http\Controllers\Api\Guest\Vybe
 */
final class VybeTypeController extends BaseController implements VybeTypeControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting vybe types
         */
        $vybeTypeListItems = VybeTypeList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($vybeTypeListItems, new VybeTypeTransformer),
            trans('validations/api/guest/vybe/type/index.result.success')
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
         * Getting vybe type
         */
        $vybeTypeListItem = VybeTypeList::getItem($id);

        /**
         * Checking vybe type existence
         */
        if (!$vybeTypeListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/vybe/type/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($vybeTypeListItem, new VybeTypeTransformer),
            trans('validations/api/guest/vybe/type/show.result.success')
        );
    }
}
