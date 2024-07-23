<?php

namespace App\Http\Controllers\Api\Guest\Language;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Language\Interfaces\LanguageControllerInterface;
use App\Http\Requests\Api\Guest\Language\IndexRequest;
use App\Lists\Language\LanguageList;
use App\Transformers\Api\Guest\Language\LanguageListItemTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class LanguageController
 *
 * @package App\Http\Controllers\Api\Guest\Language
 */
final class LanguageController extends BaseController implements LanguageControllerInterface
{
    /**
     * @param IndexRequest $request
     *
     * @return JsonResponse
     */
    public function index(
        IndexRequest $request
    ) : JsonResponse
    {
        /**
         * Checking translatable parameter existence
         */
        if ($request->input('translatable') === true) {

            /**
             * Getting languages
             */
            $languageListItems = LanguageList::getTranslatableItems();

            return $this->respondWithSuccess(
                $this->transformCollection($languageListItems, new LanguageListItemTransformer),
                trans('validations/api/guest/language/index.result.success')
            );
        }

        /**
         * Getting languages
         */
        $languageListItems = LanguageList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($languageListItems, new LanguageListItemTransformer),
            trans('validations/api/guest/language/index.result.success')
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
         * Getting language
         */
        $languageListItem = LanguageList::getItem($id);

        /**
         * Checking language existence
         */
        if (!$languageListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/language/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($languageListItem, new LanguageListItemTransformer),
            trans('validations/api/guest/language/show.result.success')
        );
    }
}
