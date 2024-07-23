<?php

namespace App\Transformers\Api\Admin\User\Statistic\Block;

use App\Transformers\BaseTransformer;

/**
 * Class FinishedSalesTransformer
 *
 * @package App\Transformers\Api\Admin\User\Statistic\Block
 */
class FinishedSalesTransformer extends BaseTransformer
{
    /**
     * @param array $data
     *
     * @return array
     */
    public function transform(array $data) : array
    {
        return [
            'amount'     => $data['amount'],
            'total'      => $data['total'],
            'percentage' => $data['percentage']
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'statistic_finished_sales';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'statistic_finished_sales';
    }
}
