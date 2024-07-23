<?php

namespace App\Transformers\Api\General\Setting\Billing;

use App\Models\MySql\PhoneCode;
use App\Transformers\BaseTransformer;

/**
 * Class PhoneCodeTransformer
 *
 * @package App\Transformers\Api\General\Setting\Billing
 */
class PhoneCodeTransformer extends BaseTransformer
{
    /**
     * @param PhoneCode $phoneCode
     *
     * @return array
     */
    public function transform(PhoneCode $phoneCode) : array
    {
        return [
            'id'   => $phoneCode->id,
            'code' => $phoneCode->code
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'phone_code';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'phone_codes';
    }
}
