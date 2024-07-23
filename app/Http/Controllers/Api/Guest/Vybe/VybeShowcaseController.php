<?php

namespace App\Http\Controllers\Api\Guest\Vybe;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Vybe\Interfaces\VybeShowcaseControllerInterface;
use App\Lists\Vybe\Showcase\VybeShowcaseList;
use App\Transformers\Api\Guest\Vybe\Showcase\VybeShowcaseTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class VybeShowcaseController
 *
 * @package App\Http\Controllers\Api\Guest\Vybe
 */
final class VybeShowcaseController extends BaseController implements VybeShowcaseControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting vybe showcases
         */
        $vybeShowcaseListItems = VybeShowcaseList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($vybeShowcaseListItems, new VybeShowcaseTransformer),
            trans('validations/api/guest/vybe/showcase/index.result.success')
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
         * Getting vybe showcase
         */
        $vybeShowcaseListItem = VybeShowcaseList::getItem($id);

        /**
         * Checking vybe showcase existence
         */
        if (!$vybeShowcaseListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/vybe/showcase/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($vybeShowcaseListItem, new VybeShowcaseTransformer),
            trans('validations/api/guest/vybe/showcase/show.result.success')
        );
    }
}
