<?php

namespace App\Transformers\Api\Guest\User\User;

use App\Models\MySql\Timezone\TimezoneOffset;
use App\Transformers\BaseTransformer;

/**
 * Class TimezoneOffsetTransformer
 *
 * @package App\Transformers\Api\Guest\User\User
 */
class TimezoneOffsetTransformer extends BaseTransformer
{
    /**
     * @param TimezoneOffset $timezoneOffset
     *
     * @return array
     */
    public function transform(TimezoneOffset $timezoneOffset) : array
    {
        return [
            'id'     => $timezoneOffset->id,
            'code'   => $timezoneOffset->code,
            'name'   => $timezoneOffset->name,
            'offset' => $timezoneOffset->offset
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'timezone_offset';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'timezone_offsets';
    }
}
