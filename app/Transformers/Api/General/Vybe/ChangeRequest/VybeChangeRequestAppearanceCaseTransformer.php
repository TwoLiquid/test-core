<?php

namespace App\Transformers\Api\General\Vybe\ChangeRequest;

use App\Exceptions\DatabaseException;
use App\Models\MongoDb\Vybe\Request\Change\VybeChangeRequestAppearanceCase;
use App\Repositories\Platform\PlatformRepository;
use App\Repositories\Suggestion\CsauSuggestionRepository;
use App\Services\Vybe\VybeChangeRequestService;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class VybeChangeRequestAppearanceCaseTransformer
 *
 * @package App\Transformers\Api\General\Vybe\ChangeRequest
 */
class VybeChangeRequestAppearanceCaseTransformer extends BaseTransformer
{
    /**
     * @var CsauSuggestionRepository
     */
    protected CsauSuggestionRepository $csauSuggestionRepository;

    /**
     * @var PlatformRepository
     */
    protected PlatformRepository $platformRepository;

    /**
     * @var VybeChangeRequestService
     */
    protected VybeChangeRequestService $vybeChangeRequestService;

    /**
     * VybeChangeRequestAppearanceCaseTransformer constructor
     */
    public function __construct()
    {
        /** @var CsauSuggestionRepository csauSuggestionRepository */
        $this->csauSuggestionRepository = new CsauSuggestionRepository();

        /** @var PlatformRepository platformRepository */
        $this->platformRepository = new PlatformRepository();

        /** @var VybeChangeRequestService vybeChangeRequestService */
        $this->vybeChangeRequestService = new VybeChangeRequestService();
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'appearance',
        'platforms',
        'price_status',
        'unit',
        'unit_csau_status',
        'unit_status',
        'platforms_status',
        'description_status',
        'city_place',
        'previous_city_place',
        'city_place_status',
        'appearance_case_status'
    ];

    /**
     * @param VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase
     *
     * @return array
     */
    public function transform(VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase) : array
    {
        return [
            'id'                     => $vybeChangeRequestAppearanceCase->_id,
            'price'                  => $vybeChangeRequestAppearanceCase->price,
            'previous_price'         => $vybeChangeRequestAppearanceCase->previous_price,
            'price_status_id'        => $vybeChangeRequestAppearanceCase->price_status_id,
            'unit_id'                => $vybeChangeRequestAppearanceCase->unit_id,
            'previous_unit_id'       => $vybeChangeRequestAppearanceCase->previous_unit_id,
            'unit_suggestion'        => $vybeChangeRequestAppearanceCase->unit_suggestion,
            'unit_status_id'         => $vybeChangeRequestAppearanceCase->unit_status_id,
            'description'            => $vybeChangeRequestAppearanceCase->description,
            'previous_description'   => $vybeChangeRequestAppearanceCase->previous_description,
            'description_status_id'  => $vybeChangeRequestAppearanceCase->description_status_id,
            'platforms_ids'          => $vybeChangeRequestAppearanceCase->platforms_ids,
            'previous_platforms_ids' => $vybeChangeRequestAppearanceCase->previous_platforms_ids,
            'platforms_status_id'    => $vybeChangeRequestAppearanceCase->platforms_status_id,
            'same_location'          => $vybeChangeRequestAppearanceCase->same_location,
            'previous_same_location' => $vybeChangeRequestAppearanceCase->previous_same_location,
            'city_place_id'          => $vybeChangeRequestAppearanceCase->city_place_id,
            'previous_city_place_id' => $vybeChangeRequestAppearanceCase->previous_city_place_id,
            'city_place_status_id'   => $vybeChangeRequestAppearanceCase->city_place_status_id,
            'enabled'                => $vybeChangeRequestAppearanceCase->enabled,
            'previous_enabled'       => $vybeChangeRequestAppearanceCase->previous_enabled,
            'csau_suggestion_id'     => $vybeChangeRequestAppearanceCase->csau_suggestion_id
        ];
    }

    /**
     * @param VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase
     *
     * @return Item|null
     */
    public function includeAppearance(VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase) : ?Item
    {
        $vybeAppearance = $vybeChangeRequestAppearanceCase->getAppearance();

        return $vybeAppearance ? $this->item($vybeAppearance, new VybeAppearanceTransformer) : null;
    }

    /**
     * @param VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase
     *
     * @return Collection|null
     *
     * @throws DatabaseException
     */
    public function includePlatforms(VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase) : ?Collection
    {
        $platforms = null;

        if ($vybeChangeRequestAppearanceCase->platforms_ids) {
            $platforms = $this->platformRepository->getByIds(
                $vybeChangeRequestAppearanceCase->platforms_ids
            );
        }

        return $platforms ? $this->collection($platforms, new PlatformTransformer) : null;
    }

    /**
     * @param VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase
     *
     * @return Item|null
     */
    public function includePriceStatus(VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase) : ?Item
    {
        $priceStatus = $vybeChangeRequestAppearanceCase->getPriceStatus();

        return $priceStatus ? $this->item($priceStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase
     *
     * @return Item|null
     */
    public function includeUnit(VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase) : ?Item
    {
        $unit = $vybeChangeRequestAppearanceCase->unit;

        return $unit ? $this->item($unit, new UnitTransformer) : null;
    }

    /**
     * @param VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase
     *
     * @return Item|null
     */
    public function includeUnitCsauStatus(VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase) : ?Item
    {
        $unitCsauStatus = null;

        if ($vybeChangeRequestAppearanceCase->relationLoaded('csauSuggestion')) {
            if ($vybeChangeRequestAppearanceCase->csauSuggestion) {
                $unitCsauStatus = $vybeChangeRequestAppearanceCase->csauSuggestion->getUnitStatus();
            }
        }

        return $unitCsauStatus ? $this->item($unitCsauStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase
     *
     * @return Item|null
     */
    public function includeUnitStatus(VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase) : ?Item
    {
        $unitStatus = $vybeChangeRequestAppearanceCase->getUnitStatus();

        return $unitStatus ? $this->item($unitStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase
     *
     * @return Item|null
     */
    public function includePlatformsStatus(VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase) : ?Item
    {
        $platformsStatus = $vybeChangeRequestAppearanceCase->getPlatformsStatus();

        return $platformsStatus ? $this->item($platformsStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase
     *
     * @return Item|null
     */
    public function includeDescriptionStatus(VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase) : ?Item
    {
        $descriptionStatus = $vybeChangeRequestAppearanceCase->getDescriptionStatus();

        return $descriptionStatus ? $this->item($descriptionStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase
     *
     * @return Item|null
     */
    public function includeCityPlace(VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase) : ?Item
    {
        $cityPlace = null;

        if ($vybeChangeRequestAppearanceCase->relationLoaded('cityPlace')) {
            $cityPlace = $vybeChangeRequestAppearanceCase->cityPlace;
        }

        return $cityPlace ? $this->item($cityPlace, new CityPlaceTransformer) : null;
    }

    /**
     * @param VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase
     *
     * @return Item|null
     */
    public function includePreviousCityPlace(VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase) : ?Item
    {
        $previousCityPlace = null;

        if ($vybeChangeRequestAppearanceCase->relationLoaded('previousCityPlace')) {
            $previousCityPlace = $vybeChangeRequestAppearanceCase->previousCityPlace;
        }

        return $previousCityPlace ? $this->item($previousCityPlace, new CityPlaceTransformer) : null;
    }

    /**
     * @param VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase
     *
     * @return Item|null
     */
    public function includeCityPlaceStatus(VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase) : ?Item
    {
        $cityPlaceStatus = $vybeChangeRequestAppearanceCase->getCityPlaceStatus();

        return $cityPlaceStatus ? $this->item($cityPlaceStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase
     *
     * @return Item|null
     */
    public function includeAppearanceCaseStatus(VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase) : ?Item
    {
        $appearanceCaseStatus = $this->vybeChangeRequestService->getAppearanceCaseStatus(
            $vybeChangeRequestAppearanceCase
        );

        return $this->item($appearanceCaseStatus, new RequestFieldStatusTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe_change_request_appearance_case';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybe_change_request_appearance_cases';
    }
}
