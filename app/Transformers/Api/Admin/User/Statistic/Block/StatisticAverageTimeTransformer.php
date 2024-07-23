<?php

namespace App\Transformers\Api\Admin\User\Statistic\Block;

use App\Transformers\BaseTransformer;

/**
 * Class StatisticAverageTimeTransformer
 *
 * @package App\Transformers\Api\Admin\User\Statistic\Block
 */
class StatisticAverageTimeTransformer extends BaseTransformer
{
    /**
     * @param array $data
     *
     * @return array
     */
    public function transform(array $data) : array
    {
        return [
            'seconds' => $data['seconds']
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'statistic_average_time';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'statistic_pages';
    }
}
