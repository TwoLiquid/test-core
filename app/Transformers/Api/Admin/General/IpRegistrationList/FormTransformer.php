<?php

namespace App\Transformers\Api\Admin\General\IpRegistrationList;

use App\Lists\Account\Status\AccountStatusList;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;

/**
 * Class FormTransformer
 *
 * @package App\Transformers\Api\Admin\General\IpRegistrationList
 */
class FormTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'account_statuses'
    ];

    /**
     * @return array
     */
    public function transform() : array
    {
        return [];
    }

    /**
     * @return Collection|null
     */
    public function includeAccountStatuses() : ?Collection
    {
        $accountStatuses = AccountStatusList::getItems();

        return $this->collection($accountStatuses, new AccountStatusTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'form';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'forms';
    }
}
