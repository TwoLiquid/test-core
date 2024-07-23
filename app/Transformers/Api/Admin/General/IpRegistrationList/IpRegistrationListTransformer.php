<?php

namespace App\Transformers\Api\Admin\General\IpRegistrationList;

use App\Models\MySql\IpRegistrationList;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class IpRegistrationListTransformer
 *
 * @package App\Transformers\Api\Admin\General\IpRegistrationList
 */
class IpRegistrationListTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'user',
        'duplicates'
    ];

    /**
     * @param IpRegistrationList $ipRegistrationList
     *
     * @return array
     */
    public function transform(IpRegistrationList $ipRegistrationList) : array
    {
        return [
            'id'         => $ipRegistrationList->id,
            'ip_address' => $ipRegistrationList->ip_address,
            'vpn'        => $ipRegistrationList->vpn
        ];
    }

    /**
     * @param IpRegistrationList $ipRegistrationList
     *
     * @return Item|null
     */
    public function includeUser(IpRegistrationList $ipRegistrationList) : ?Item
    {
        $user = null;

        if ($ipRegistrationList->relationLoaded('user')) {
            $user = $ipRegistrationList->user;
        }

        return $user ? $this->item($user, new UserTransformer) : null;
    }

    /**
     * @param IpRegistrationList $ipRegistrationList
     *
     * @return Collection|null
     */
    public function includeDuplicates(IpRegistrationList $ipRegistrationList) : ?Collection
    {
        $duplicates = null;

        if ($ipRegistrationList->relationLoaded('duplicates')) {
            $duplicates = $ipRegistrationList->duplicates->except([
                'id' => $ipRegistrationList->id
            ]);
        }

        return $duplicates ? $this->collection($duplicates, new UserDuplicateTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'ip_registration_list';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'ip_registration_list';
    }
}
