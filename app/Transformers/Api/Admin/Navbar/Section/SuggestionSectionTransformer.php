<?php

namespace App\Transformers\Api\Admin\Navbar\Section;

use App\Transformers\BaseTransformer;

/**
 * Class SuggestionSectionTransformer
 *
 * @package App\Transformers\Api\Admin\Navbar\Section
 */
class SuggestionSectionTransformer extends BaseTransformer
{
    /**
     * @param array $suggestions
     *
     * @return array
     */
    public function transform(array $suggestions) : array
    {
        return $suggestions;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'suggestion';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'suggestions';
    }
}
