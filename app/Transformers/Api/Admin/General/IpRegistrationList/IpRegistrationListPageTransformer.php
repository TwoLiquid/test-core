<?php

namespace App\Transformers\Api\Admin\General\IpRegistrationList;

use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class IpRegistrationListPageTransformer
 *
 * @package App\Transformers\Api\Admin\General\IpRegistrationList
 */
class IpRegistrationListPageTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $ipRegistrationList;

    /**
     * @param EloquentCollection $ipRegistrationList
     */
    public function __construct(
        EloquentCollection $ipRegistrationList
    )
    {
        /** @var EloquentCollection ipRegistrationList */
        $this->ipRegistrationList = $ipRegistrationList;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'form',
        'ip_registration_list'
    ];

    /**
     * @return array
     */
    public function transform() : array
    {
        return [];
    }

    /**
     * @return Item
     */
    public function includeForm() : Item
    {
        return $this->item([], new FormTransformer);
    }

    /**
     * @return Collection
     */
    public function includeIpRegistrationList() : Collection
    {
        return $this->collection($this->ipRegistrationList, new IpRegistrationListTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'ip_registration_list_page';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'ip_registration_list_pages';
    }
}
