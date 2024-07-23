<?php

namespace App\Repositories\AppearanceCase;

use App\Exceptions\DatabaseException;
use App\Lists\Vybe\Appearance\VybeAppearanceListItem;
use App\Models\MySql\AppearanceCase;
use App\Models\MySql\Place\CityPlace;
use App\Models\MySql\Platform;
use App\Models\MySql\Unit;
use App\Models\MySql\Vybe\Vybe;
use App\Repositories\AppearanceCase\Interfaces\AppearanceCaseRepositoryInterface;
use App\Repositories\BaseRepository;
use Exception;

/**
 * Class AppearanceCaseRepository
 *
 * @package App\Repositories\AppearanceCase
 */
class AppearanceCaseRepository extends BaseRepository implements AppearanceCaseRepositoryInterface
{
    /**
     * AppearanceCaseRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.appearanceCase.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return AppearanceCase|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?AppearanceCase
    {
        try {
            return AppearanceCase::query()
                ->with([
                    'vybe',
                    'unit',
                    'cityPlace.timezone.offsets'
                ])
                ->where('id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/appearanceCase.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     * @param VybeAppearanceListItem $vybeAppearanceListItem
     *
     * @return AppearanceCase|null
     *
     * @throws DatabaseException
     */
    public function findForVybeByAppearance(
        Vybe $vybe,
        VybeAppearanceListItem $vybeAppearanceListItem
    ) : ?AppearanceCase
    {
        try {
            return AppearanceCase::query()
                ->with([
                    'unit',
                    'cityPlace.timezone.offsets'
                ])
                ->where('vybe_id', '=', $vybe->id)
                ->where('appearance_id', '=', $vybeAppearanceListItem->id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/appearanceCase.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     * @param VybeAppearanceListItem $vybeAppearanceListItem
     * @param Unit|null $unit
     * @param CityPlace|null $cityPlace
     * @param float|null $price
     * @param string|null $description
     * @param bool|null $sameLocation
     * @param bool|null $enabled
     *
     * @return AppearanceCase|null
     *
     * @throws DatabaseException
     */
    public function store(
        Vybe $vybe,
        VybeAppearanceListItem $vybeAppearanceListItem,
        ?Unit $unit,
        ?CityPlace $cityPlace,
        ?float $price,
        ?string $description,
        ?bool $sameLocation,
        ?bool $enabled
    ) : ?AppearanceCase
    {
        try {
            return AppearanceCase::query()->create([
                'vybe_id'       => $vybe->id,
                'appearance_id' => $vybeAppearanceListItem->id,
                'unit_id'       => $unit?->id,
                'city_place_id' => $cityPlace?->place_id,
                'price'         => $price,
                'description'   => $description ? trim($description) : null,
                'same_location' => is_null($sameLocation) ? false : $sameLocation,
                'enabled'       => $enabled
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/appearanceCase.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param AppearanceCase $appearanceCase
     * @param Vybe|null $vybe
     * @param VybeAppearanceListItem|null $vybeAppearanceListItem
     * @param Unit|null $unit
     * @param CityPlace|null $cityPlace
     * @param float|null $price
     * @param string|null $description
     * @param bool|null $sameLocation
     * @param bool|null $enabled
     *
     * @return AppearanceCase
     *
     * @throws DatabaseException
     */
    public function update(
        AppearanceCase $appearanceCase,
        ?Vybe $vybe,
        ?VybeAppearanceListItem $vybeAppearanceListItem,
        ?Unit $unit,
        ?CityPlace $cityPlace,
        ?float $price,
        ?string $description,
        ?bool $sameLocation,
        ?bool $enabled
    ) : AppearanceCase
    {
        try {
            $appearanceCase->update([
                'vybe_id'       => $vybe ? $vybe->id : $appearanceCase->vybe_id,
                'appearance_id' => $vybeAppearanceListItem ? $vybeAppearanceListItem->id : $appearanceCase->appearance_id,
                'unit_id'       => $unit ? $unit->id : $appearanceCase->unit_id,
                'city_place_id' => $cityPlace ? $cityPlace->place_id : $appearanceCase->city_place_id,
                'price'         => $price ?: $appearanceCase->price,
                'description'   => $description ? trim($description) : $appearanceCase->description,
                'same_location' => $sameLocation ?: $appearanceCase->same_location,
                'enabled'       => $enabled ?: $appearanceCase->enabled
            ]);

            return $appearanceCase;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/appearanceCase.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     * @param VybeAppearanceListItem $vybeAppearanceListItem
     * @param CityPlace|null $cityPlace
     *
     * @throws DatabaseException
     */
    public function updateCityForVybeByAppearance(
        Vybe $vybe,
        VybeAppearanceListItem $vybeAppearanceListItem,
        ?CityPlace $cityPlace
    ) : void
    {
        try {
            AppearanceCase::query()
                ->where('vybe_id', '=', $vybe->id)
                ->where('appearance_id', '=', $vybeAppearanceListItem->id)
                ->update([
                    'city_place_id' => $cityPlace?->id
                ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/appearanceCase.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     * @param VybeAppearanceListItem $vybeAppearanceListItem
     * @param Unit|null $unit
     *
     * @throws DatabaseException
     */
    public function updateUnitForVybeByAppearance(
        Vybe $vybe,
        VybeAppearanceListItem $vybeAppearanceListItem,
        ?Unit $unit
    ) : void
    {
        try {
            AppearanceCase::query()
                ->where('vybe_id', '=', $vybe->id)
                ->where('appearance_id', '=', $vybeAppearanceListItem->id)
                ->update([
                    'unit_id' => $unit?->id
                ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/appearanceCase.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param AppearanceCase $appearanceCase
     * @param Platform $platform
     *
     * @throws DatabaseException
     */
    public function attachPlatform(
        AppearanceCase $appearanceCase,
        Platform $platform
    ) : void
    {
        try {
            $appearanceCase->platforms()->sync([
                $platform->id
            ], false);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/appearanceCase.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param AppearanceCase $appearanceCase
     * @param array $platformsIds
     * @param bool|null $detaching
     *
     * @throws DatabaseException
     */
    public function attachPlatforms(
        AppearanceCase $appearanceCase,
        array $platformsIds,
        ?bool $detaching = false
    ) : void
    {
        try {
            $appearanceCase->platforms()->sync(
                $platformsIds,
                $detaching
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/appearanceCase.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param AppearanceCase $appearanceCase
     * @param Platform $platform
     *
     * @throws DatabaseException
     */
    public function detachPlatform(
        AppearanceCase $appearanceCase,
        Platform $platform
    ) : void
    {
        try {
            $appearanceCase->platforms()->detach([
                $platform->id
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/appearanceCase.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param AppearanceCase $appearanceCase
     * @param array $platformsIds
     *
     * @throws DatabaseException
     */
    public function detachPlatforms(
        AppearanceCase $appearanceCase,
        array $platformsIds
    ) : void
    {
        try {
            $appearanceCase->platforms()->detach(
                $platformsIds
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/appearanceCase.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function deleteForVybe(
        Vybe $vybe
    ) : bool
    {
        try {
            return AppearanceCase::query()
                ->where('vybe_id', '=', $vybe->id)
                ->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/appearanceCase.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function deleteForceForVybe(
        Vybe $vybe
    ) : bool
    {
        try {
            return AppearanceCase::query()
                ->where('vybe_id', '=', $vybe->id)
                ->forceDelete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/appearanceCase.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param AppearanceCase $appearanceCase
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function deleteForce(
        AppearanceCase $appearanceCase
    ) : bool
    {
        try {
            return $appearanceCase->forceDelete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/appearanceCase.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param AppearanceCase $appearanceCase
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        AppearanceCase $appearanceCase
    ) : bool
    {
        try {
            return $appearanceCase->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/appearanceCase.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
