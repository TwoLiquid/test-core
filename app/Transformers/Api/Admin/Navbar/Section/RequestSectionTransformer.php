<?php

namespace App\Transformers\Api\Admin\Navbar\Section;

use App\Transformers\BaseTransformer;

/**
 * Class RequestSectionTransformer
 *
 * @package App\Transformers\Api\Admin\Navbar\Section
 */
class RequestSectionTransformer extends BaseTransformer
{
    /**
     * @param array $requests
     *
     * @return array
     */
    public function transform(array $requests) : array
    {
        return $requests;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'request';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'requests';
    }
}
