<?php

namespace App\Repositories\AppearanceCase\Interfaces;

use App\Lists\Vybe\Appearance\VybeAppearanceListItem;
use App\Models\MySql\AppearanceCase;
use App\Models\MySql\Place\CityPlace;
use App\Models\MySql\Platform;
use App\Models\MySql\Unit;
use App\Models\MySql\Vybe\Vybe;

/**
 * Interface AppearanceCaseRepositoryInterface
 *
 * @package App\Repositories\AppearanceCase\Interfaces
 */
interface AppearanceCaseRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return AppearanceCase|null
     */
    public function findById(
        ?int $id
    ) : ?AppearanceCase;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param Vybe $vybe
     * @param VybeAppearanceListItem $vybeAppearanceListItem
     *
     * @return AppearanceCase|null
     */
    public function findForVybeByAppearance(
        Vybe $vybe,
        VybeAppearanceListItem $vybeAppearanceListItem
    ) : ?AppearanceCase;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
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
    ) : ?AppearanceCase;

    /**
     * This method provides updating row
     * with an eloquent model
     *
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
    ) : AppearanceCase;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param Vybe $vybe
     * @param VybeAppearanceListItem $vybeAppearanceListItem
     * @param CityPlace|null $cityPlace
     */
    public function updateCityForVybeByAppearance(
        Vybe $vybe,
        VybeAppearanceListItem $vybeAppearanceListItem,
        ?CityPlace $cityPlace
    ) : void;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param Vybe $vybe
     * @param VybeAppearanceListItem $vybeAppearanceListItem
     * @param Unit|null $unit
     */
    public function updateUnitForVybeByAppearance(
        Vybe $vybe,
        VybeAppearanceListItem $vybeAppearanceListItem,
        ?Unit $unit
    ) : void;

    /**
     * This method provides attaching an existing model
     * with a current model with belongs to many relations
     *
     * @param AppearanceCase $appearanceCase
     * @param Platform $platform
     */
    public function attachPlatform(
        AppearanceCase $appearanceCase,
        Platform $platform
    ) : void;

    /**
     * This method provides attaching an existing models
     * with a current model with belongs to many relations
     *
     * @param AppearanceCase $appearanceCase
     * @param array $platformsIds
     * @param bool|null $detaching
     */
    public function attachPlatforms(
        AppearanceCase $appearanceCase,
        array $platformsIds,
        ?bool $detaching
    ) : void;

    /**
     * This method provides detaching existing model
     * with a current model with belongs to many relations
     *
     * @param AppearanceCase $appearanceCase
     * @param Platform $platform
     */
    public function detachPlatform(
        AppearanceCase $appearanceCase,
        Platform $platform
    ) : void;

    /**
     * This method provides detaching existing model
     * with a current model with belongs to many relations
     *
     * @param AppearanceCase $appearanceCase
     * @param array $platformsIds
     */
    public function detachPlatforms(
        AppearanceCase $appearanceCase,
        array $platformsIds
    ) : void;

    /**
     * This method provides deleting existing rows
     * with an eloquent model by certain query
     *
     * @param Vybe $vybe
     *
     * @return bool
     */
    public function deleteForVybe(
        Vybe $vybe
    ) : bool;

    /**
     * This method provides deleting existing rows
     * with an eloquent model by certain query
     *
     * @param Vybe $vybe
     *
     * @return bool
     */
    public function deleteForceForVybe(
        Vybe $vybe
    ) : bool;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param AppearanceCase $appearanceCase
     *
     * @return bool
     */
    public function deleteForce(
        AppearanceCase $appearanceCase
    ) : bool;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param AppearanceCase $appearanceCase
     *
     * @return bool
     */
    public function delete(
        AppearanceCase $appearanceCase
    ) : bool;
}
