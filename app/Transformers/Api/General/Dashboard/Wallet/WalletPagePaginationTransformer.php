<?php

namespace App\Transformers\Api\General\Dashboard\Wallet;

use App\Transformers\BaseTransformer;

/**
 * Class WalletPagePaginationTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Wallet
 */
class WalletPagePaginationTransformer extends BaseTransformer
{
    /**
     * @param array $pagination
     *
     * @return array
     */
    public function transform(array $pagination) : array
    {
        return [
            'page'     => $pagination['page'],
            'by'       => $pagination['by'],
            'total'    => $pagination['total'],
            'lastPage' => $pagination['lastPage']
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'pagination';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'pagination';
    }
}
