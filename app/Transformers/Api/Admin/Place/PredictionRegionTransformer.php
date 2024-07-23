<?php

namespace App\Transformers\Api\Admin\Place;

use App\Transformers\BaseTransformer;

/**
 * Class PredictionRegionTransformer
 *
 * @package App\Transformers\Api\Admin\Place
 */
class PredictionRegionTransformer extends BaseTransformer
{
    /**
     * @param array $prediction
     *
     * @return array
     */
    public function transform(array $prediction) : array
    {
        return [
            'description' => $prediction['description'],
            'place_id'    => $prediction['place_id'],
            'region'      => $prediction['structured_formatting']['main_text']
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'prediction';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'predictions';
    }
}
