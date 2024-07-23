<?php

namespace App\Transformers\Api\General\Dashboard\Finance\Buyer\Invoice\Tip\Pdf;

use App\Transformers\BaseTransformer;

/**
 * Class CompanyCredentialsTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Finance\Buyer\Invoice\Tip\Pdf
 */
class CompanyCredentialsTransformer extends BaseTransformer
{
    /**
     * @return array
     */
    public function transform() : array
    {
        return [
            'name'    => config('company.credentials.name'),
            'address' => config('company.credentials.address'),
            'country' => config('company.credentials.country'),
            'phone'   => config('company.credentials.phone'),
            'btw'     => config('company.credentials.btw'),
            'email'   => config('company.credentials.email'),
            'site'    => config('company.credentials.site')
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'credentials';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'credentials';
    }
}
