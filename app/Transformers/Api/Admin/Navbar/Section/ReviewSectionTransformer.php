<?php

namespace App\Transformers\Api\Admin\Navbar\Section;

use App\Transformers\BaseTransformer;

/**
 * Class ReviewSectionTransformer
 *
 * @package App\Transformers\Api\Admin\Navbar\Section
 */
class ReviewSectionTransformer extends BaseTransformer
{
    /**
     * @param array $reviews
     *
     * @return array
     */
    public function transform(array $reviews) : array
    {
        return $reviews;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'review';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'reviews';
    }
}
