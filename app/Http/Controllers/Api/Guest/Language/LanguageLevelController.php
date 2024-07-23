<?php

namespace App\Http\Controllers\Api\Guest\Language;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Language\Interfaces\LanguageLevelControllerInterface;
use App\Lists\Language\Level\LanguageLevelList;
use App\Transformers\Api\Guest\Language\LanguageLevelTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class LanguageLevelController
 *
 * @package App\Http\Controllers\Api\Guest\Language
 */
final class LanguageLevelController extends BaseController implements LanguageLevelControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting language levels
         */
        $languageLevelListItems = LanguageLevelList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($languageLevelListItems, new LanguageLevelTransformer),
            trans('validations/api/guest/language/level/index.result.success')
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
         * Getting language level
         */
        $languageLevelListItem = LanguageLevelList::getItem($id);

        /**
         * Checking language level existence
         */
        if (!$languageLevelListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/language/level/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($languageLevelListItem, new LanguageLevelTransformer),
            trans('validations/api/guest/language/level/show.result.success')
        );
    }
}
