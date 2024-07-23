<?php

namespace App\Repositories\Vybe;

use App\Exceptions\DatabaseException;
use App\Lists\Request\Field\Status\RequestFieldStatusList;
use App\Lists\Request\Field\Status\RequestFieldStatusListItem;
use App\Lists\Vybe\Appearance\VybeAppearanceListItem;
use App\Models\MongoDb\Suggestion\CsauSuggestion;
use App\Models\MongoDb\Vybe\Request\Publish\VybePublishRequest;
use App\Models\MongoDb\Vybe\Request\Publish\VybePublishRequestAppearanceCase;
use App\Models\MySql\Place\CityPlace;
use App\Models\MySql\Unit;
use App\Repositories\BaseRepository;
use App\Repositories\Vybe\Interfaces\VybePublishRequestAppearanceCaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class VybePublishRequestAppearanceCaseRepository
 *
 * @package App\Repositories\Vybe
 */
class VybePublishRequestAppearanceCaseRepository extends BaseRepository implements VybePublishRequestAppearanceCaseRepositoryInterface
{
    /**
     * VybePublishRequestAppearanceCaseRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.vybePublishRequestAppearanceCase.perPage');
    }

    /**
     * @param string|null $id
     *
     * @return VybePublishRequestAppearanceCase|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?string $id
    ) : ?VybePublishRequestAppearanceCase
    {
        try {
            return VybePublishRequestAppearanceCase::query()
                ->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybePublishRequestAppearanceCase.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllForVybePublishRequest(
        VybePublishRequest $vybePublishRequest
    ) : Collection
    {
        try {
            return VybePublishRequestAppearanceCase::query()
                ->where('vybe_publish_request_id', '=', $vybePublishRequest->_id)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybePublishRequestAppearanceCase.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
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
    ) : ?VybePublishRequestAppearanceCase
    {
        try {
            return VybePublishRequestAppearanceCase::query()->create([
                'vybe_publish_request_id' => $vybePublishRequest->_id,
                'appearance_id'           => $vybeAppearanceListItem->id,
                'price'                   => $price,
                'price_status_id'         => RequestFieldStatusList::getPendingItem()->id,
                'unit_id'                 => $unit?->id,
                'unit_suggestion'         => $unitSuggestion,
                'unit_status_id'          => RequestFieldStatusList::getPendingItem()->id,
                'description'             => $description,
                'description_status_id'   => $description ?
                    RequestFieldStatusList::getPendingItem()->id :
                    null,
                'platforms_ids'           => $platformsIds,
                'platforms_status_id'     => $platformsIds ?
                    RequestFieldStatusList::getPendingItem()->id :
                    null,
                'same_location'           => $sameLocation,
                'city_place_id'           => $cityPlace?->place_id,
                'enabled'                 => $enabled,
                'city_place_status_id'    => $vybeAppearanceListItem->isRealLife() ?
                    RequestFieldStatusList::getPendingItem()->id :
                    null
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybePublishRequestAppearanceCase.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase
     * @param RequestFieldStatusListItem $priceStatus
     * @param RequestFieldStatusListItem $unitStatus
     * @param RequestFieldStatusListItem|null $descriptionStatus
     * @param RequestFieldStatusListItem|null $platformsStatus
     * @param RequestFieldStatusListItem|null $cityPlaceStatus
     *
     * @return VybePublishRequestAppearanceCase
     *
     * @throws DatabaseException
     */
    public function updateStatuses(
        VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase,
        RequestFieldStatusListItem $priceStatus,
        RequestFieldStatusListItem $unitStatus,
        ?RequestFieldStatusListItem $descriptionStatus,
        ?RequestFieldStatusListItem $platformsStatus,
        ?RequestFieldStatusListItem $cityPlaceStatus
    ) : VybePublishRequestAppearanceCase
    {
        try {
            $vybePublishRequestAppearanceCase->update([
                'price_status_id'       => $priceStatus->id,
                'unit_status_id'        => $unitStatus->id,
                'description_status_id' => $descriptionStatus?->id,
                'platforms_status_id'   => $platformsStatus?->id,
                'city_place_status_id'  => $cityPlaceStatus?->id
            ]);

            return $vybePublishRequestAppearanceCase;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybePublishRequestAppearanceCase.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase
     * @param CsauSuggestion $csauSuggestion
     *
     * @return VybePublishRequestAppearanceCase|null
     *
     * @throws DatabaseException
     */
    public function updateCsauSuggestion(
        VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase,
        CsauSuggestion $csauSuggestion
    ) : ?VybePublishRequestAppearanceCase
    {
        try {
            $vybePublishRequestAppearanceCase->update([
                'csau_suggestion_id' => $csauSuggestion->_id
            ]);

            return $vybePublishRequestAppearanceCase;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybePublishRequestAppearanceCase.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase
     * @param Unit|null $unit
     * @param string|null $unitSuggestion
     *
     * @return VybePublishRequestAppearanceCase|null
     *
     * @throws DatabaseException
     */
    public function updateUnit(
        VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase,
        ?Unit $unit,
        ?string $unitSuggestion
    ) : ?VybePublishRequestAppearanceCase
    {
        try {
            $vybePublishRequestAppearanceCase->update([
                'unit_id'         => $unit?->id,
                'unit_suggestion' => $unitSuggestion
            ]);

            return $vybePublishRequestAppearanceCase;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybePublishRequestAppearanceCase.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase
     * @param CityPlace|null $cityPlace
     *
     * @return VybePublishRequestAppearanceCase|null
     *
     * @throws DatabaseException
     */
    public function updateCityPlace(
        VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase,
        ?CityPlace $cityPlace
    ) : ?VybePublishRequestAppearanceCase
    {
        try {
            $vybePublishRequestAppearanceCase->update([
                'city_place_id' => $cityPlace?->place_id
            ]);

            return $vybePublishRequestAppearanceCase;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybePublishRequestAppearanceCase.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function deleteForVybePublishRequest(
        VybePublishRequest $vybePublishRequest
    ) : bool
    {
        try {
            return VybePublishRequestAppearanceCase::query()
                ->where('vybe_publish_request_id', '=', $vybePublishRequest->_id)
                ->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybePublishRequestAppearanceCase.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase
    ) : bool
    {
        try {
            return $vybePublishRequestAppearanceCase->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybePublishRequestAppearanceCase.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
