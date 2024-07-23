<?php

namespace App\Http\Controllers\Api\Guest\Vybe;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Vybe\Interfaces\VybeOrderAcceptControllerInterface;
use App\Lists\Vybe\OrderAccept\VybeOrderAcceptList;
use App\Transformers\Api\Guest\Vybe\OrderAccept\VybeOrderAcceptTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class VybeOrderAcceptController
 *
 * @package App\Http\Controllers\Api\Guest\Vybe
 */
final class VybeOrderAcceptController extends BaseController implements VybeOrderAcceptControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting vybe accesses
         */
        $vybeOrderAcceptListItems = VybeOrderAcceptList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($vybeOrderAcceptListItems, new VybeOrderAcceptTransformer),
            trans('validations/api/guest/vybe/orderAccept/index.result.success')
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
         * Getting vybe access
         */
        $vybeOrderAcceptListItem = VybeOrderAcceptList::getItem($id);

        /**
         * Checking vybe order accept existence
         */
        if (!$vybeOrderAcceptListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/vybe/orderAccept/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($vybeOrderAcceptListItem, new VybeOrderAcceptTransformer),
            trans('validations/api/guest/vybe/orderAccept/show.result.success')
        );
    }
}
