<?php

namespace App\Http\Controllers\Api\Guest\Vybe;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Vybe\Interfaces\VybeAccessControllerInterface;
use App\Lists\Vybe\Access\VybeAccessList;
use App\Transformers\Api\Guest\Vybe\Access\VybeAccessTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class VybeAccessController
 *
 * @package App\Http\Controllers\Api\Guest\Vybe
 */
final class VybeAccessController extends BaseController implements VybeAccessControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting vybe accesses
         */
        $vybeAccessListItems = VybeAccessList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($vybeAccessListItems, new VybeAccessTransformer),
            trans('validations/api/guest/vybe/access/index.result.success')
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
        $vybeAccessListItem = VybeAccessList::getItem($id);

        /**
         * Checking vybe access existence
         */
        if (!$vybeAccessListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/vybe/access/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($vybeAccessListItem, new VybeAccessTransformer),
            trans('validations/api/guest/vybe/access/show.result.success')
        );
    }
}
