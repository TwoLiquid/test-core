<?php

namespace App\Transformers\Api\Guest\Auth\Register\Form;

use App\Exceptions\DatabaseException;
use App\Lists\Gender\GenderList;
use App\Repositories\Place\CountryPlaceRepository;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;

/**
 * Class RegisterFormTransformer
 *
 * @package App\Transformers\Api\Guest\Auth\Register\Form
 */
class RegisterFormTransformer extends BaseTransformer
{
    /**
     * @var CountryPlaceRepository
     */
    protected CountryPlaceRepository $countryPlaceRepository;

    /**
     * RegisterFormTransformer constructor
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
        'genders',
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
     *
     * @throws DatabaseException
     */
    public function includeCountryPlaces() : ?Collection
    {
        $countryPlaces = $this->countryPlaceRepository->getAllWithoutExcluded();

        return $this->collection($countryPlaces, new CountryPlaceTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeGenders() : ?Collection
    {
        $genders = GenderList::getItems();

        return $this->collection($genders, new GenderTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'register_form';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'register_forms';
    }
}
