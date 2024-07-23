<?php

namespace App\Microservices\Media\Transformers;

use App\Microservices\Media\Responses\VybeVideoResponse;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class VybeVideoTransformer
 *
 * @package App\Transformers\Api
 */
class VybeVideoTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'thumbnail'
    ];

    /**
     * @param VybeVideoResponse $vybeVideoResponse
     *
     * @return array
     */
    public function transform(VybeVideoResponse $vybeVideoResponse) : array
    {
        return [
            'id'       => $vybeVideoResponse->id,
            'url'      => $vybeVideoResponse->url,
            'duration' => $vybeVideoResponse->duration,
            'mime'     => $vybeVideoResponse->mime,
            'main'     => $vybeVideoResponse->main,
            'declined' => $vybeVideoResponse->declined
        ];
    }

    /**
     * @param VybeVideoResponse $vybeVideoResponse
     *
     * @return Item|null
     */
    public function includeThumbnail(VybeVideoResponse $vybeVideoResponse): ?Item
    {
        return isset($vybeVideoResponse->thumbnail) ?
            $this->item(
                $vybeVideoResponse->thumbnail,
                new VybeVideoThumbnailTransformer
            ) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe_video';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybe_videos';
    }
}
