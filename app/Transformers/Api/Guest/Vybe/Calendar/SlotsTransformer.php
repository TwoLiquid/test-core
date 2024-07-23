<?php

namespace App\Transformers\Api\Guest\Vybe\Calendar;

use App\Transformers\BaseTransformer;

/**
 * Class SlotsTransformer
 *
 * @package App\Transformers\Api\Guest\Vybe\Calendar
 */
class SlotsTransformer extends BaseTransformer
{
    /**
     * @param array $slots
     *
     * @return array
     */
    public function transform(array $slots) : array
    {
        return $slots;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'slots';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'slots';
    }
}
