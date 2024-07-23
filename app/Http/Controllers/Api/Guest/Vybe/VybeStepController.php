<?php

namespace App\Http\Controllers\Api\Guest\Vybe;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Vybe\Interfaces\VybeStepControllerInterface;
use App\Lists\Vybe\Step\VybeStepList;
use App\Transformers\Api\Guest\Vybe\Step\VybeStepTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class VybeStepController
 *
 * @package App\Http\Controllers\Api\Guest\Vybe
 */
final class VybeStepController extends BaseController implements VybeStepControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting vybe steps
         */
        $vybeStepListItems = VybeStepList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($vybeStepListItems, new VybeStepTransformer),
            trans('validations/api/guest/vybe/step/index.result.success')
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
         * Getting vybe step
         */
        $vybeStepListItem = VybeStepList::getItem($id);

        /**
         * Checking vybe step existence
         */
        if (!$vybeStepListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/vybe/step/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($vybeStepListItem, new VybeStepTransformer),
            trans('validations/api/guest/vybe/step/show.result.success')
        );
    }
}
