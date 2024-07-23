<?php

namespace App\Repositories\Vybe\Interfaces;

use App\Lists\Request\Field\Status\RequestFieldStatusListItem;
use App\Lists\Vybe\Appearance\VybeAppearanceListItem;
use App\Models\MongoDb\Suggestion\CsauSuggestion;
use App\Models\MongoDb\Vybe\Request\Change\VybeChangeRequest;
use App\Models\MongoDb\Vybe\Request\Change\VybeChangeRequestAppearanceCase;
use App\Models\MySql\Place\CityPlace;
use App\Models\MySql\Unit;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface VybeChangeRequestAppearanceCaseRepositoryInterface
 *
 * @package App\Repositories\Vybe\Interfaces
 */
interface VybeChangeRequestAppearanceCaseRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param string|null $id
     *
     * @return VybeChangeRequestAppearanceCase|null
     */
    public function findById(
        ?string $id
    ) : ?VybeChangeRequestAppearanceCase;

    /**
     * This method provides getting rows
     * with an eloquent model
     *
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Collection
     */
    public function getAllForVybeChangeRequest(
        VybeChangeRequest $vybeChangeRequest
    ) : Collection;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param VybeChangeRequest $vybeChangeRequest
     * @param VybeAppearanceListItem $vybeAppearanceListItem
     * @param float|null $price
     * @param float|null $previousPrice
     * @param Unit|null $unit
     * @param Unit|null $previousUnit
     * @param string|null $unitSuggestion
     * @param string|null $description
     * @param string|null $previousDescription
     * @param array|null $platformsIds
     * @param array|null $previousPlatformsIds
     * @param bool|null $sameLocation
     * @param bool|null $previousSameLocation
     * @param CityPlace|null $cityPlace
     * @param CityPlace|null $previousCityPlace
     * @param bool|null $enabled
     * @param bool|null $previousEnabled
     *
     * @return VybeChangeRequestAppearanceCase|null
     */
    public function store(
        VybeChangeRequest $vybeChangeRequest,
        VybeAppearanceListItem $vybeAppearanceListItem,
        ?float $price,
        ?float $previousPrice,
        ?Unit $unit,
        ?Unit $previousUnit,
        ?string $unitSuggestion,
        ?string $description,
        ?string $previousDescription,
        ?array $platformsIds,
        ?array $previousPlatformsIds,
        ?bool $sameLocation,
        ?bool $previousSameLocation,
        ?CityPlace $cityPlace,
        ?CityPlace $previousCityPlace,
        ?bool $enabled,
        ?bool $previousEnabled
    ) : ?VybeChangeRequestAppearanceCase;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase
     * @param RequestFieldStatusListItem|null $priceStatus
     * @param RequestFieldStatusListItem|null $unitStatus
     * @param RequestFieldStatusListItem|null $descriptionStatus
     * @param RequestFieldStatusListItem|null $platformsStatus
     * @param RequestFieldStatusListItem|null $cityPlaceStatus
     *
     * @return VybeChangeRequestAppearanceCase
     */
    public function updateStatuses(
        VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase,
        ?RequestFieldStatusListItem $priceStatus,
        ?RequestFieldStatusListItem $unitStatus,
        ?RequestFieldStatusListItem $descriptionStatus,
        ?RequestFieldStatusListItem $platformsStatus,
        ?RequestFieldStatusListItem $cityPlaceStatus
    ) : VybeChangeRequestAppearanceCase;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase
     * @param CsauSuggestion $csauSuggestion
     *
     * @return VybeChangeRequestAppearanceCase|null
     */
    public function updateCsauSuggestion(
        VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase,
        CsauSuggestion $csauSuggestion
    ) : ?VybeChangeRequestAppearanceCase;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase
     * @param Unit|null $unit
     * @param string|null $unitSuggestion
     *
     * @return VybeChangeRequestAppearanceCase|null
     */
    public function updateUnit(
        VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase,
        ?Unit $unit,
        ?string $unitSuggestion
    ) : ?VybeChangeRequestAppearanceCase;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase
     * @param CityPlace|null $cityPlace
     *
     * @return VybeChangeRequestAppearanceCase|null
     */
    public function updateCityPlace(
        VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase,
        ?CityPlace $cityPlace
    ) : ?VybeChangeRequestAppearanceCase;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return bool
     */
    public function deleteForVybeChangeRequest(
        VybeChangeRequest $vybeChangeRequest
    ) : bool;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase
     *
     * @return bool
     */
    public function delete(
        VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase
    ) : bool;
}
