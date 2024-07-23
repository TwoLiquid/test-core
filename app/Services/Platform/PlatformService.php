<?php

namespace App\Services\Platform;

use App\Exceptions\DatabaseException;
use App\Models\MySql\AppearanceCase;
use App\Models\MySql\Platform;
use App\Models\MySql\Vybe\Vybe;
use App\Repositories\Platform\PlatformRepository;
use App\Services\Platform\Interfaces\PlatformServiceInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class PlatformService
 *
 * @package App\Services\Platform
 */
class PlatformService implements PlatformServiceInterface
{
    /**
     * @var PlatformRepository
     */
    protected PlatformRepository $platformRepository;

    /**
     * PlatformService constructor
     */
    public function __construct()
    {
        /** @var PlatformRepository platformRepository */
        $this->platformRepository = new PlatformRepository();
    }

    /**
     * @param array $platformsItems
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function updatePositions(
        array $platformsItems
    ) : Collection
    {
        $platforms = new Collection();

        /** @var array $platformItem */
        foreach ($platformsItems as $platformItem) {

            /**
             * Getting platform
             */
            $platform = $this->platformRepository->findById(
                $platformItem['id']
            );

            /**
             * Add a platform to a collection
             */
            $platforms->add(
                $platform
            );
        }

        return $platforms;
    }

    /**
     * @param Vybe $vybe
     *
     * @return Collection
     */
    public function getByVybe(
        Vybe $vybe
    ) : Collection
    {
        /**
         * Preparing a platform collection
         */
        $platforms = new Collection();

        if ($vybe->relationLoaded('appearanceCases')) {

            /** @var AppearanceCase $appearanceCase */
            foreach ($vybe->appearanceCases as $appearanceCase) {

                /** @var Platform $platform */
                foreach ($appearanceCase->platforms as $platform) {

                    /**
                     * Adding a platform to a collection
                     */
                    $platforms->push(
                        $platform
                    );
                }
            }
        }

        return $platforms->unique();
    }

    /**
     * @param Collection $appearanceCases
     *
     * @return Collection
     */
    public function getByAppearanceCases(
        Collection $appearanceCases
    ) : Collection
    {
        /**
         * Preparing a platform collection
         */
        $platforms = new Collection();

        /** @var AppearanceCase $appearanceCase */
        foreach ($appearanceCases as $appearanceCase) {

            /** @var Platform $platform */
            foreach ($appearanceCase->platforms as $platform) {

                /**
                 * Adding a platform to a collection
                 */
                $platforms->push(
                    $platform
                );
            }
        }

        return $platforms->unique();
    }
}
