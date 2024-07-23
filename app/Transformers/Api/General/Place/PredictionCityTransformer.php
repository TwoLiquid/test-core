<?php

namespace App\Transformers\Api\General\Place;

use App\Transformers\BaseTransformer;

/**
 * Class PredictionCityTransformer
 *
 * @package App\Transformers\Api\General\Place
 */
class PredictionCityTransformer extends BaseTransformer
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
            'country'     => $prediction['terms'][count($prediction['terms']) - 1]['value'],
            'city'        => $prediction['structured_formatting']['main_text']
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
