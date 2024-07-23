<?php

namespace App\Repositories\Vybe\Interfaces;

use App\Lists\Request\Field\Status\RequestFieldStatusListItem;
use App\Lists\Vybe\Appearance\VybeAppearanceListItem;
use App\Models\MongoDb\Suggestion\CsauSuggestion;
use App\Models\MongoDb\Vybe\Request\Publish\VybePublishRequest;
use App\Models\MongoDb\Vybe\Request\Publish\VybePublishRequestAppearanceCase;
use App\Models\MySql\Place\CityPlace;
use App\Models\MySql\Unit;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface VybePublishRequestAppearanceCaseRepositoryInterface
 *
 * @package App\Repositories\Vybe\Interfaces
 */
interface VybePublishRequestAppearanceCaseRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param string|null $id
     *
     * @return VybePublishRequestAppearanceCase|null
     */
    public function findById(
        ?string $id
    ) : ?VybePublishRequestAppearanceCase;

    /**
     * This method provides getting rows
     * with an eloquent model
     *
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Collection
     */
    public function getAllForVybePublishRequest(
        VybePublishRequest $vybePublishRequest
    ) : Collection;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param VybePublishRequest $vybePublishRequest
     * @param VybeAppearanceListItem $vybeAppearanceListItem
     * @param float|null $price
     * @param Unit|null $unit
     * @param string|null $unitSuggestion
     * @param string|null $description
     * @param array|null $platformsIds
     * @param bool|null $sameLocation
     * @param CityPlace|null $cityPlace
     * @param bool|null $enabled
     *
     * @return VybePublishRequestAppearanceCase|null
     */
    public function store(
        VybePublishRequest $vybePublishRequest,
        VybeAppearanceListItem $vybeAppearanceListItem,
        ?float $price,
        ?Unit $unit,
        ?string $unitSuggestion,
        ?string $description,
        ?array $platformsIds,
        ?bool $sameLocation,
        ?CityPlace $cityPlace,
        ?bool $enabled
    ) : ?VybePublishRequestAppearanceCase;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase
     * @param RequestFieldStatusListItem $priceStatus
     * @param RequestFieldStatusListItem $unitStatus
     * @param RequestFieldStatusListItem|null $descriptionStatus
     * @param RequestFieldStatusListItem|null $platformsStatus
     * @param RequestFieldStatusListItem|null $cityPlaceStatus
     *
     * @return VybePublishRequestAppearanceCase
     */
    public function updateStatuses(
        VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase,
        RequestFieldStatusListItem $priceStatus,
        RequestFieldStatusListItem $unitStatus,
        ?RequestFieldStatusListItem $descriptionStatus,
        ?RequestFieldStatusListItem $platformsStatus,
        ?RequestFieldStatusListItem $cityPlaceStatus
    ) : VybePublishRequestAppearanceCase;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase
     * @param CsauSuggestion $csauSuggestion
     *
     * @return VybePublishRequestAppearanceCase|null
     */
    public function updateCsauSuggestion(
        VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase,
        CsauSuggestion $csauSuggestion
    ) : ?VybePublishRequestAppearanceCase;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase
     * @param Unit|null $unit
     * @param string|null $unitSuggestion
     *
     * @return VybePublishRequestAppearanceCase|null
     */
    public function updateUnit(
        VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase,
        ?Unit $unit,
        ?string $unitSuggestion
    ) : ?VybePublishRequestAppearanceCase;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase
     * @param CityPlace|null $cityPlace
     *
     * @return VybePublishRequestAppearanceCase|null
     */
    public function updateCityPlace(
        VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase,
        ?CityPlace $cityPlace
    ) : ?VybePublishRequestAppearanceCase;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return bool
     */
    public function deleteForVybePublishRequest(
        VybePublishRequest $vybePublishRequest
    ) : bool;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase
     *
     * @return bool
     */
    public function delete(
        VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase
    ) : bool;
}
