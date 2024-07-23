<?php

namespace App\Http\Controllers\Api\Guest\PersonalityTrait;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\PersonalityTrait\Interfaces\PersonalityTraitControllerInterface;
use App\Lists\PersonalityTrait\PersonalityTraitList;
use App\Transformers\Api\Guest\PersonalityTrait\PersonalityTraitListItemTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class PersonalityTraitController
 *
 * @package App\Http\Controllers\Api\Guest\PersonalityTrait
 */
final class PersonalityTraitController extends BaseController implements PersonalityTraitControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting personality traits
         */
        $personalityTraitListItems = PersonalityTraitList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($personalityTraitListItems, new PersonalityTraitListItemTransformer),
            trans('validations/api/guest/personalityTrait/index.result.success')
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
         * Getting personality trait
         */
        $personalityTraitListItem = PersonalityTraitList::getItem($id);

        /**
         * Checking personality trait existence
         */
        if (!$personalityTraitListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/personalityTrait/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($personalityTraitListItem, new PersonalityTraitListItemTransformer),
            trans('validations/api/guest/personalityTrait/show.result.success')
        );
    }
}
