<?php

namespace App\Http\Controllers\Api\Guest\Vybe;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Vybe\Interfaces\VybeAppearanceControllerInterface;
use App\Lists\Vybe\Appearance\VybeAppearanceList;
use App\Transformers\Api\Guest\Vybe\Appearance\VybeAppearanceTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class VybeAppearanceController
 *
 * @package App\Http\Controllers\Api\Guest\Vybe
 */
final class VybeAppearanceController extends BaseController implements VybeAppearanceControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting vybe appearances
         */
        $vybeAppearanceListItems = VybeAppearanceList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($vybeAppearanceListItems, new VybeAppearanceTransformer),
            trans('validations/api/guest/vybe/appearance/index.result.success')
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
         * Getting vybe appearance
         */
        $vybeAppearanceListItem = VybeAppearanceList::getItem($id);

        /**
         * Checking vybe appearance existence
         */
        if (!$vybeAppearanceListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/vybe/appearance/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($vybeAppearanceListItem, new VybeAppearanceTransformer),
            trans('validations/api/guest/vybe/appearance/show.result.success')
        );
    }
}
