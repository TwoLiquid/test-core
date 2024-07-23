<?php

namespace App\Transformers\Api\Guest\Home\Category\Vybe;

use App\Models\MySql\Media\VybeVideo;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class VybeVideoTransformer
 *
 * @package App\Transformers\Api\Guest\Home\Category\Vybe
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
     * @param VybeVideo $vybeVideo
     *
     * @return array
     */
    public function transform(VybeVideo $vybeVideo) : array
    {
        return [
            'id'       => $vybeVideo->id,
            'url'      => $vybeVideo->url,
            'duration' => $vybeVideo->duration,
            'mime'     => $vybeVideo->mime,
            'main'     => $vybeVideo->main,
            'declined' => $vybeVideo->declined
        ];
    }

    /**
     * @param VybeVideo $vybeVideo
     *
     * @return Item|null
     */
    public function includeThumbnail(VybeVideo $vybeVideo): ?Item
    {
        $thumbnail = null;

        if ($vybeVideo->relationLoaded('thumbnail')) {
            $thumbnail = $vybeVideo->thumbnail;
        }

        return $thumbnail ? $this->item($thumbnail, new VybeVideoThumbnailTransformer) : null;
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
