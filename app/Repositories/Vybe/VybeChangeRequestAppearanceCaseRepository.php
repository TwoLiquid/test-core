<?php

namespace App\Repositories\Vybe;

use App\Exceptions\DatabaseException;
use App\Lists\Request\Field\Status\RequestFieldStatusList;
use App\Lists\Request\Field\Status\RequestFieldStatusListItem;
use App\Lists\Vybe\Appearance\VybeAppearanceListItem;
use App\Models\MongoDb\Suggestion\CsauSuggestion;
use App\Models\MongoDb\Vybe\Request\Change\VybeChangeRequest;
use App\Models\MongoDb\Vybe\Request\Change\VybeChangeRequestAppearanceCase;
use App\Models\MySql\Place\CityPlace;
use App\Models\MySql\Unit;
use App\Repositories\BaseRepository;
use App\Repositories\Vybe\Interfaces\VybeChangeRequestAppearanceCaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class VybeChangeRequestAppearanceCaseRepository
 *
 * @package App\Repositories\Vybe
 */
class VybeChangeRequestAppearanceCaseRepository extends BaseRepository implements VybeChangeRequestAppearanceCaseRepositoryInterface
{
    /**
     * VybeChangeRequestAppearanceCaseRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.vybeChangeRequestAppearanceCase.perPage');
    }

    /**
     * @param string|null $id
     *
     * @return VybeChangeRequestAppearanceCase|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?string $id
    ) : ?VybeChangeRequestAppearanceCase
    {
        try {
            return VybeChangeRequestAppearanceCase::query()
                ->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeChangeRequestAppearanceCase.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllForVybeChangeRequest(
        VybeChangeRequest $vybeChangeRequest
    ) : Collection
    {
        try {
            return VybeChangeRequestAppearanceCase::query()
                ->where('vybe_change_request_id', '=', $vybeChangeRequest->_id)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeChangeRequestAppearanceCase.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
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
    ) : ?VybeChangeRequestAppearanceCase
    {
        try {
            return VybeChangeRequestAppearanceCase::query()->create([
                'vybe_change_request_id' => $vybeChangeRequest->_id,
                'appearance_id'          => $vybeAppearanceListItem->id,
                'price'                  => $price,
                'previous_price'         => $previousPrice,
                'price_status_id'        => $price ? RequestFieldStatusList::getPendingItem()->id : null,
                'unit_id'                => $unit?->id,
                'previous_unit_id'       => $previousUnit?->id,
                'unit_suggestion'        => $unitSuggestion,
                'unit_status_id'         => ($unit || $unitSuggestion) ? RequestFieldStatusList::getPendingItem()->id : null,
                'description'            => $description,
                'previous_description'   => $previousDescription,
                'description_status_id'  => $description ? RequestFieldStatusList::getPendingItem()->id : null,
                'platforms_ids'          => $platformsIds,
                'previous_platforms_ids' => $previousPlatformsIds,
                'platforms_status_id'    => $platformsIds ? RequestFieldStatusList::getPendingItem()->id : null,
                'same_location'          => $sameLocation,
                'previous_same_location' => $previousSameLocation,
                'city_place_id'          => $cityPlace?->place_id,
                'previous_city_place_id' => $previousCityPlace?->place_id,
                'city_place_status_id'   => $cityPlace ? RequestFieldStatusList::getPendingItem()->id : null,
                'enabled'                => $enabled,
                'previous_enabled'       => $previousEnabled
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeChangeRequestAppearanceCase.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase
     * @param RequestFieldStatusListItem|null $priceStatus
     * @param RequestFieldStatusListItem|null $unitStatus
     * @param RequestFieldStatusListItem|null $descriptionStatus
     * @param RequestFieldStatusListItem|null $platformsStatus
     * @param RequestFieldStatusListItem|null $cityPlaceStatus
     *
     * @return VybeChangeRequestAppearanceCase
     *
     * @throws DatabaseException
     */
    public function updateStatuses(
        VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase,
        ?RequestFieldStatusListItem $priceStatus,
        ?RequestFieldStatusListItem $unitStatus,
        ?RequestFieldStatusListItem $descriptionStatus,
        ?RequestFieldStatusListItem $platformsStatus,
        ?RequestFieldStatusListItem $cityPlaceStatus
    ) : VybeChangeRequestAppearanceCase
    {
        try {
            $vybeChangeRequestAppearanceCase->update([
                'price_status_id'       => $priceStatus?->id,
                'unit_status_id'        => $unitStatus?->id,
                'description_status_id' => $descriptionStatus?->id,
                'platforms_status_id'   => $platformsStatus?->id,
                'city_place_status_id'  => $cityPlaceStatus?->id
            ]);

            return $vybeChangeRequestAppearanceCase;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeChangeRequestAppearanceCase.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase
     * @param CsauSuggestion $csauSuggestion
     *
     * @return VybeChangeRequestAppearanceCase|null
     *
     * @throws DatabaseException
     */
    public function updateCsauSuggestion(
        VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase,
        CsauSuggestion $csauSuggestion
    ) : ?VybeChangeRequestAppearanceCase
    {
        try {
            $vybeChangeRequestAppearanceCase->update([
                'csau_suggestion_id' => $csauSuggestion->_id
            ]);

            return $vybeChangeRequestAppearanceCase;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeChangeRequestAppearanceCase.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase
     * @param Unit|null $unit
     * @param string|null $unitSuggestion
     *
     * @return VybeChangeRequestAppearanceCase|null
     *
     * @throws DatabaseException
     */
    public function updateUnit(
        VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase,
        ?Unit $unit,
        ?string $unitSuggestion
    ) : ?VybeChangeRequestAppearanceCase
    {
        try {
            $vybeChangeRequestAppearanceCase->update([
                'unit_id'         => $unit?->id,
                'unit_suggestion' => $unitSuggestion
            ]);

            return $vybeChangeRequestAppearanceCase;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeChangeRequestAppearanceCase.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase
     * @param CityPlace|null $cityPlace
     *
     * @return VybeChangeRequestAppearanceCase|null
     *
     * @throws DatabaseException
     */
    public function updateCityPlace(
        VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase,
        ?CityPlace $cityPlace
    ) : ?VybeChangeRequestAppearanceCase
    {
        try {
            $vybeChangeRequestAppearanceCase->update([
                'city_place_id' => $cityPlace?->place_id
            ]);

            return $vybeChangeRequestAppearanceCase;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeChangeRequestAppearanceCase.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function deleteForVybeChangeRequest(
        VybeChangeRequest $vybeChangeRequest
    ) : bool
    {
        try {
            return VybeChangeRequestAppearanceCase::query()
                ->where('vybe_change_request_id', '=', $vybeChangeRequest->_id)
                ->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeChangeRequestAppearanceCase.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase
    ) : bool
    {
        try {
            return $vybeChangeRequestAppearanceCase->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeChangeRequestAppearanceCase.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
