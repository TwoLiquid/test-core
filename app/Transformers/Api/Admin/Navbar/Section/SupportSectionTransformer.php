<?php

namespace App\Transformers\Api\Admin\Navbar\Section;

use App\Transformers\BaseTransformer;

/**
 * Class SupportSectionTransformer
 *
 * @package App\Transformers\Api\Admin\Navbar\Section
 */
class SupportSectionTransformer extends BaseTransformer
{
    /**
     * @param array $supports
     *
     * @return array
     */
    public function transform(array $supports) : array
    {
        return $supports;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'support';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'supports';
    }
}
