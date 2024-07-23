<?php

namespace App\Microservices\Media\Transformers;

use App\Microservices\Media\Responses\CategoryIconResponse;
use App\Transformers\BaseTransformer;

/**
 * Class CategoryIconTransformer
 *
 * @package App\Microservices\Media\Transformers
 */
class CategoryIconTransformer extends BaseTransformer
{
    /**
     * @param CategoryIconResponse $categoryIconResponse
     *
     * @return array
     */
    public function transform(CategoryIconResponse $categoryIconResponse) : array
    {
        return [
            'id'          => $categoryIconResponse->id,
            'category_id' => $categoryIconResponse->categoryId,
            'url'         => $categoryIconResponse->url,
            'mime'        => $categoryIconResponse->mime
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'category_icon';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'category_icons';
    }
}
