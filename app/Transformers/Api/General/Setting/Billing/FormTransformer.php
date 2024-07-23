<?php

namespace App\Transformers\Api\General\Setting\Billing;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Billing;
use App\Repositories\PhoneCode\PhoneCodeRepository;
use App\Repositories\Place\CountryPlaceRepository;
use App\Repositories\Place\RegionPlaceRepository;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;

/**
 * Class FormTransformer
 *
 * @package App\Transformers\Api\General\Setting\Billing
 */
class FormTransformer extends BaseTransformer
{
    /**
     * @var Billing|null
     */
    protected ?Billing $billing;

    /**
     * @var CountryPlaceRepository
     */
    protected CountryPlaceRepository $countryPlaceRepository;

    /**
     * @var PhoneCodeRepository
     */
    protected PhoneCodeRepository $phoneCodeRepository;

    /**
     * @var RegionPlaceRepository
     */
    protected RegionPlaceRepository $regionPlaceRepository;

    /**
     * FormTransformer constructor
     *
     * @param Billing|null $billing
     */
    public function __construct(
        ?Billing $billing = null
    )
    {
        /** @var Billing billing */
        $this->billing = $billing;

        /** @var CountryPlaceRepository countryPlaceRepository */
        $this->countryPlaceRepository = new CountryPlaceRepository();

        /** @var PhoneCodeRepository phoneCodeRepository */
        $this->phoneCodeRepository = new PhoneCodeRepository();

        /** @var RegionPlaceRepository regionPlaceRepository */
        $this->regionPlaceRepository = new RegionPlaceRepository();
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'country_places',
        'region_places'
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
     *
     * @throws DatabaseException
     */
    public function includeRegionPlaces() : ?Collection
    {
        $regionPlaces = null;

        if ($this->billing) {
            if ($this->billing->relationLoaded('countryPlace')) {
                $countryPlace = $this->billing
                    ->countryPlace;

                if ($countryPlace) {
                    $regionPlaces = $this->regionPlaceRepository->getAllByCountryPlace(
                        $countryPlace
                    );
                }
            }
        }

        return $regionPlaces ? $this->collection($regionPlaces, new RegionPlaceTransformer) : null;
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
