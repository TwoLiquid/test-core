<?php

namespace App\Transformers\Api\Admin\Vybe\PublishRequest;

use App\Exceptions\DatabaseException;
use App\Lists\Language\LanguageListItem;
use App\Models\MongoDb\Vybe\Request\Publish\VybePublishRequestAppearanceCase;
use App\Repositories\Platform\PlatformRepository;
use App\Repositories\Suggestion\CsauSuggestionRepository;
use App\Services\Vybe\VybePublishRequestService;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

/**
 * Class VybePublishRequestAppearanceCaseTransformer
 *
 * @package App\Transformers\Api\Admin\Vybe\PublishRequest
 */
class VybePublishRequestAppearanceCaseTransformer extends BaseTransformer
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
     * @var VybePublishRequestService
     */
    protected VybePublishRequestService $vybePublishRequestService;

    /**
     * VybePublishRequestAppearanceCaseTransformer constructor
     */
    public function __construct()
    {
        /** @var CsauSuggestionRepository csauSuggestionRepository */
        $this->csauSuggestionRepository = new CsauSuggestionRepository();

        /** @var PlatformRepository platformRepository */
        $this->platformRepository = new PlatformRepository();

        /** @var VybePublishRequestService vybePublishRequestService */
        $this->vybePublishRequestService = new VybePublishRequestService();
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'appearance',
        'price_status',
        'unit',
        'unit_csau_status',
        'unit_status',
        'platforms',
        'platforms_status',
        'description_status',
        'city_place',
        'city_place_status',
        'appearance_case_status'
    ];

    /**
     * @param VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase
     *
     * @return array
     */
    public function transform(VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase) : array
    {
        /** @var LanguageListItem $language */
        $language = $vybePublishRequestAppearanceCase->vybePublishRequest
            ->vybe
            ->user
            ->getLanguage();

        return [
            'id'              => $vybePublishRequestAppearanceCase->_id,
            'price'           => $vybePublishRequestAppearanceCase->price,
            'unit_suggestion' => $vybePublishRequestAppearanceCase->unit_suggestion ? [
                $language->iso => $vybePublishRequestAppearanceCase->unit_suggestion
            ] : null,
            'description'     => $vybePublishRequestAppearanceCase->description,
            'same_location'   => $vybePublishRequestAppearanceCase->same_location,
            'enabled'         => $vybePublishRequestAppearanceCase->enabled
        ];
    }

    /**
     * @param VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase
     *
     * @return Item|null
     */
    public function includeAppearance(VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase) : ?Item
    {
        $vybeAppearance = $vybePublishRequestAppearanceCase->getAppearance();

        return $vybeAppearance ? $this->item($vybeAppearance, new VybeAppearanceTransformer) : null;
    }

    /**
     * @param VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase
     *
     * @return Item|null
     */
    public function includePriceStatus(VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase) : ?Item
    {
        $priceStatus = $vybePublishRequestAppearanceCase->getPriceStatus();

        return $priceStatus ? $this->item($priceStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase
     *
     * @return Item|null
     */
    public function includeUnit(VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase) : ?Item
    {
        $unit = null;

        if ($vybePublishRequestAppearanceCase->relationLoaded('unit')) {
            $unit = $vybePublishRequestAppearanceCase->unit;
        }

        return $unit ? $this->item($unit, new UnitTransformer) : null;
    }

    /**
     * @param VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase
     *
     * @return Item|null
     */
    public function includeUnitCsauStatus(VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase) : ?Item
    {
        $unitCsauStatus = null;

        if ($vybePublishRequestAppearanceCase->relationLoaded('csauSuggestion')) {
            $csauSuggestion = $vybePublishRequestAppearanceCase->csauSuggestion;

            if ($csauSuggestion) {
                $unitCsauStatus = $vybePublishRequestAppearanceCase->csauSuggestion->getUnitStatus();
            }
        }

        return $unitCsauStatus ? $this->item($unitCsauStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase
     *
     * @return Item|null
     */
    public function includeUnitStatus(VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase) : ?Item
    {
        $unitStatus = $vybePublishRequestAppearanceCase->getUnitStatus();

        return $unitStatus ? $this->item($unitStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase
     *
     * @return Collection|null
     *
     * @throws DatabaseException
     */
    public function includePlatforms(VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase) : ?Collection
    {
        $platforms = null;

        if ($vybePublishRequestAppearanceCase->platforms_ids) {
            $platforms = $this->platformRepository->getByIds(
                $vybePublishRequestAppearanceCase->platforms_ids
            );
        }

        return $platforms ? $this->collection($platforms, new PlatformTransformer) : null;
    }

    /**
     * @param VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase
     *
     * @return Item|null
     */
    public function includePlatformsStatus(VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase) : ?Item
    {
        $platformsStatus = $vybePublishRequestAppearanceCase->getPlatformsStatus();

        return $platformsStatus ? $this->item($platformsStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase
     *
     * @return Item|null
     */
    public function includeDescriptionStatus(VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase) : ?Item
    {
        $descriptionStatus = $vybePublishRequestAppearanceCase->getDescriptionStatus();

        return $descriptionStatus ? $this->item($descriptionStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase
     *
     * @return Item|null
     */
    public function includeCityPlace(VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase) : ?Item
    {
        $cityPlace = null;

        if ($vybePublishRequestAppearanceCase->relationLoaded('cityPlace')) {
            $cityPlace = $vybePublishRequestAppearanceCase->cityPlace;
        }

        return $cityPlace ? $this->item($cityPlace, new CityPlaceTransformer) : null;
    }

    /**
     * @param VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase
     *
     * @return Item|null
     */
    public function includeCityPlaceStatus(VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase) : ?Item
    {
        $cityPlaceStatus = $vybePublishRequestAppearanceCase->getCityPlaceStatus();

        return $cityPlaceStatus ? $this->item($cityPlaceStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase
     *
     * @return Item|null
     */
    public function includeAppearanceCaseStatus(VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase) : ?Item
    {
        $appearanceCaseStatus = $this->vybePublishRequestService->getAppearanceCaseStatus(
            $vybePublishRequestAppearanceCase
        );

        return $this->item($appearanceCaseStatus, new RequestFieldStatusTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe_publish_request_appearance_case';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybe_publish_request_appearance_cases';
    }
}
