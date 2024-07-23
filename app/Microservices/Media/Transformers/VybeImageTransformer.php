<?php

namespace App\Microservices\Media\Transformers;

use App\Microservices\Media\Responses\VybeImageResponse;
use App\Transformers\BaseTransformer;

/**
 * Class VybeImageTransformer
 *
 * @package App\Transformers\Api
 */
class VybeImageTransformer extends BaseTransformer
{
    /**
     * @param VybeImageResponse $vybeImageResponse
     *
     * @return array
     */
    public function transform(VybeImageResponse $vybeImageResponse) : array
    {
        return [
            'id'       => $vybeImageResponse->id,
            'url'      => $vybeImageResponse->url,
            'url_min'  => $vybeImageResponse->urlMin,
            'mime'     => $vybeImageResponse->mime,
            'main'     => $vybeImageResponse->main,
            'declined' => $vybeImageResponse->declined
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe_image';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybe_images';
    }
}
