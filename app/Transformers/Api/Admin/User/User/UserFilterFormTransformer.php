<?php

namespace App\Transformers\Api\Admin\User\User;

use App\Exceptions\DatabaseException;
use App\Lists\Account\Status\AccountStatusList;
use App\Lists\User\Balance\Status\UserBalanceStatusList;
use App\Lists\User\Balance\Type\UserBalanceTypeList;
use App\Repositories\Place\CountryPlaceRepository;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;

/**
 * Class UserFilterFormTransformer
 *
 * @package App\Transformers\Api\Admin\User\User
 */
class UserFilterFormTransformer extends BaseTransformer
{
    /**
     * @var CountryPlaceRepository
     */
    protected CountryPlaceRepository $countryPlaceRepository;

    /**
     * UserFilterFormTransformer constructor
     */
    public function __construct()
    {
        /** @var CountryPlaceRepository countryPlaceRepository */
        $this->countryPlaceRepository = new CountryPlaceRepository();
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'account_statuses',
        'user_balance_types',
        'user_balance_statuses',
        'country_places'
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
     * @return Collection|null
     */
    public function includeUserBalanceTypes() : ?Collection
    {
        $userBalanceTypes = UserBalanceTypeList::getItems();

        return $this->collection($userBalanceTypes, new UserBalanceTypeTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeUserBalanceStatuses() : ?Collection
    {
        $userBalanceStatuses = UserBalanceStatusList::getItems();

        return $this->collection($userBalanceStatuses, new UserBalanceStatusTransformer);
    }

    /**
     * @return Collection|null
     *
     * @throws DatabaseException
     */
    public function includeCountryPlaces() : ?Collection
    {
        $countryPlace = $this->countryPlaceRepository->getAll();

        return $this->collection($countryPlace, new CountryPlaceTransformer);
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
