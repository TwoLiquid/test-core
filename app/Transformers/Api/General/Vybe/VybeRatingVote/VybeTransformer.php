<?php

namespace App\Transformers\Api\General\Vybe\VybeRatingVote;

use App\Models\MySql\Vybe\Vybe;
use App\Transformers\BaseTransformer;

/**
 * Class VybeTransformer
 *
 * @package App\Transformers\Api\General\Vybe\VybeRatingVote
 */
class VybeTransformer extends BaseTransformer
{
    /**
     * @param Vybe $vybe
     *
     * @return array
     */
    public function transform(Vybe $vybe) : array
    {
        return [
            'id'         => $vybe->id,
            'version'    => $vybe->version,
            'title'      => $vybe->title,
            'images_ids' => $vybe->images_ids,
            'videos_ids' => $vybe->videos_ids
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybes';
    }
}
