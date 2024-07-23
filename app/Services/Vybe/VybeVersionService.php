<?php

namespace App\Services\Vybe;

use App\Exceptions\DatabaseException;
use App\Lists\Language\LanguageList;
use App\Models\MongoDb\Vybe\VybeVersion;
use App\Models\MySql\AppearanceCase;
use App\Models\MySql\Schedule;
use App\Models\MySql\Vybe\Vybe;
use App\Repositories\Vybe\VybeVersionRepository;
use App\Services\Vybe\Interfaces\VybeVersionServiceInterface;
use App\Settings\Vybe\HandlingFeesSetting;

/**
 * Class VybeVersionService
 *
 * @package App\Services\Vybe
 */
class VybeVersionService implements VybeVersionServiceInterface
{
    /**
     * @var HandlingFeesSetting
     */
    protected HandlingFeesSetting $handlingFeesSetting;

    /**
     * @var VybeVersionRepository
     */
    protected VybeVersionRepository $vybeVersionRepository;

    /**
     * VybeVersionService constructor
     */
    public function __construct()
    {
        /** @var HandlingFeesSetting handlingFeesSetting */
        $this->handlingFeesSetting = new HandlingFeesSetting();

        /** @var VybeVersionRepository vybeVersionRepository */
        $this->vybeVersionRepository = new VybeVersionRepository();
    }

    /**
     * @param Vybe $vybe
     *
     * @return VybeVersion
     *
     * @throws DatabaseException
     */
    public function create(
        Vybe $vybe
    ) : VybeVersion
    {
        /**
         * Setting vybe handling fee
         */
        $this->handlingFeesSetting->setVybe(
            $vybe
        );

        /**
         * Getting category
         */
        $category = $vybe->category->getTranslation(
            'name',
            LanguageList::getEnglish()->iso
        );

        /**
         * Getting subcategory
         */
        $subcategory = $vybe->subcategory ?
            $vybe->subcategory->getTranslation('name', LanguageList::getEnglish()->iso) :
            null;

        /**
         * Getting activity
         */
        $activity = $vybe->activity->getTranslation(
            'name',
            LanguageList::getEnglish()->iso
        );

        /**
         * Getting devices
         */
        $devices = $vybe->devices
            ->pluck('name')
            ->values()
            ->toArray();

        /**
         * Getting an appearance cases
         */
        $appearanceCases = $this->getAppearanceCases(
            $vybe
        );

        /**
         * Getting schedules
         */
        $schedules = $this->getSchedules(
            $vybe
        );

        /**
         * Creating vybe version
         */
        return $this->vybeVersionRepository->store(
            $vybe,
            $vybe->version + 1,
            $vybe->getType()->name,
            $vybe->title,
            $category,
            $subcategory,
            $activity,
            $devices,
            $this->handlingFeesSetting->getVybeSellerHandlingFee(),
            $vybe->getPeriod()->name,
            $vybe->user_count,
            $appearanceCases,
            $schedules,
            $vybe->order_advance,
            $vybe->images_ids,
            $vybe->videos_ids,
            $vybe->getAccess()->name,
            $vybe->getShowcase()->name,
            $vybe->getOrderAccept()->name,
            $vybe->getAgeLimit()->name,
            $vybe->getStatus()->name
        );
    }

    /**
     * @param VybeVersion $vybeVersion
     *
     * @return VybeVersion|null
     *
     * @throws DatabaseException
     */
    public function getPreviousVersion(
        VybeVersion $vybeVersion
    ) : ?VybeVersion
    {
        /**
         * Getting a previous vybe version
         */
        return $this->vybeVersionRepository->findByNumber(
            $vybeVersion->vybe,
            $vybeVersion->number - 1
        );
    }

    /**
     * @param Vybe $vybe
     *
     * @return array
     */
    private function getAppearanceCases(
        Vybe $vybe
    ) : array
    {
        $appearanceCases = [];

        /** @var AppearanceCase $appearanceCase */
        foreach ($vybe->appearanceCases as $appearanceCase) {

            /**
             * Getting unit
             */
            $unit = $appearanceCase->unit->getTranslation(
                'name',
                LanguageList::getEnglish()->iso
            );

            /**
             * Getting city place
             */
            $cityPlace = $appearanceCase->cityPlace ?
                $appearanceCase->cityPlace
                    ->getTranslation(
                        'name',
                        LanguageList::getEnglish()->iso
                    ) : null;

            /**
             * Getting platforms
             */
            $platforms = $appearanceCase->platforms
                ->pluck('name')
                ->values()
                ->toArray();

            $appearanceCases[$appearanceCase->getAppearance()->code] = [
                'price'         => $appearanceCase->price,
                'unit'          => $unit,
                'description'   => $appearanceCase->description,
                'same_location' => $appearanceCase->same_location,
                'city_place'    => $cityPlace,
                'platforms'     => $platforms,
                'enabled'       => $appearanceCase->enabled
            ];
        }

        return $appearanceCases;
    }

    /**
     * @param Vybe $vybe
     *
     * @return array
     */
    private function getSchedules(
        Vybe $vybe
    ) : array
    {
        $schedules = [];

        /** @var Schedule $schedule */
        foreach ($vybe->schedules as $schedule) {
            $schedules[] = [
                'datetime_from' => $schedule->datetime_from->format('Y-m-d\TH:i:s.v\Z'),
                'datetime_to'   => $schedule->datetime_to->format('Y-m-d\TH:i:s.v\Z')
            ];
        }

        return $schedules;
    }
}
