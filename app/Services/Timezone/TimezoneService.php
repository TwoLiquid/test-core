<?php

namespace App\Services\Timezone;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Models\MySql\Timezone\Timezone;
use App\Repositories\Timezone\TimezoneOffsetRepository;
use App\Repositories\Timezone\TimezoneRepository;
use App\Services\Google\GoogleTimezoneService;
use App\Services\Google\GoogleTranslationService;
use App\Services\Timezone\Interfaces\TimezoneServiceInterface;
use App\Support\Service\Timezone\TimezoneOffsetResponse;
use Carbon\Carbon;
use Dedicated\GoogleTranslate\TranslateException;
use Spatie\GoogleTimeZone\Exceptions\GoogleTimeZoneException;

/**
 * Class TimezoneService
 *
 * @package App\Services\Timezone
 */
class TimezoneService implements TimezoneServiceInterface
{
    /**
     * @var GoogleTimezoneService
     */
    protected GoogleTimezoneService $googleTimezoneService;

    /**
     * @var GoogleTranslationService
     */
    protected GoogleTranslationService $googleTranslationService;

    /**
     * @var TimezoneOffsetRepository
     */
    protected TimezoneOffsetRepository $timezoneOffsetRepository;

    /**
     * @var TimezoneRepository
     */
    protected TimezoneRepository $timezoneRepository;

    /**
     * TimezoneService constructor
     */
    public function __construct()
    {
        /** @var GoogleTimezoneService googleTimezoneService */
        $this->googleTimezoneService = new GoogleTimezoneService();

        /** @var GoogleTranslationService googleTranslationService */
        $this->googleTranslationService = new GoogleTranslationService();

        /** @var TimezoneOffsetRepository timezoneOffsetRepository */
        $this->timezoneOffsetRepository = new TimezoneOffsetRepository();

        /** @var TimezoneRepository timezoneRepository */
        $this->timezoneRepository = new TimezoneRepository();
    }

    /**
     * @param string $externalId
     * @param float $latitude
     * @param float $longitude
     *
     * @return Timezone
     *
     * @throws BaseException
     * @throws DatabaseException
     * @throws GoogleTimeZoneException
     * @throws TranslateException
     */
    public function getOrCreate(
        string $externalId,
        float $latitude,
        float $longitude
    ) : Timezone
    {
        /**
         * Getting timezone
         */
        $timezone = $this->timezoneRepository->findByExternalId(
            trim($externalId)
        );

        /**
         * Checking timezone existence
         */
        if (!$timezone) {

            /**
             * Getting timezone offset response
             */
            $timezoneOffsetResponse = $this->getOrCreateTimezoneOffsets(
                $latitude,
                $longitude
            );

            /**
             * Creating timezone
             */
            $timezone = $this->timezoneRepository->store(
                trim($externalId),
                !is_null($timezoneOffsetResponse->getDstTimezoneOffset()),
                false
            );

            /**
             * Checking standard timezone offset existence
             */
            if ($timezoneOffsetResponse->getStandardTimezoneOffset()) {

                /**
                 * Attaching offset to timezone
                 */
                $this->timezoneRepository->attachOffset(
                    $timezone,
                    $timezoneOffsetResponse->getStandardTimezoneOffset()
                );
            }

            /**
             * Checking dst timezone offset existence
             */
            if ($timezoneOffsetResponse->getDstTimezoneOffset()) {

                /**
                 * Attaching offset to timezone
                 */
                $this->timezoneRepository->attachOffset(
                    $timezone,
                    $timezoneOffsetResponse->getDstTimezoneOffset(),
                    true
                );
            }
        }

        return $timezone;
    }

    /**
     * @param float $latitude
     * @param float $longitude
     *
     * @return TimezoneOffsetResponse
     *
     * @throws BaseException
     * @throws DatabaseException
     * @throws GoogleTimeZoneException
     * @throws TranslateException
     */
    public function getOrCreateTimezoneOffsets(
        float $latitude,
        float $longitude
    ) : TimezoneOffsetResponse
    {
        /**
         * Setting next winter and summer timestamps
         */
        $timestamps = [
            getNextYearDecember()->timestamp,
            getNextYearJuly()->timestamp
        ];

        /**
         * Preparing a timezone offset response
         */
        $timezoneOffsetResponse = new TimezoneOffsetResponse();

        /** @var string $timestamp */
        foreach ($timestamps as $timestamp) {

            /**
             * Getting google timezone response
             */
            $googleTimezoneResponse = $this->googleTimezoneService->getByCoordinates(
                $latitude,
                $longitude,
                $timestamp
            );

            /**
             * Getting timezone offset
             */
            $timezoneOffset = $this->timezoneOffsetRepository->findByName(
                $googleTimezoneResponse->timeZoneName
            );

            /**
             * Checking timezone existence
             */
            if (!$timezoneOffset) {

                /**
                 * Creating timezone offset
                 */
                $timezoneOffset = $this->timezoneOffsetRepository->store(
                    '---',
                    $this->googleTranslationService->getTranslations(
                        $googleTimezoneResponse->timeZoneName
                    ),
                    array_sum([
                        $googleTimezoneResponse->dstOffset,
                        $googleTimezoneResponse->rawOffset
                    ])
                );
            }

            /**
             * Checking google timezone response dst offset
             */
            if ($googleTimezoneResponse->dstOffset == 0) {

                /**
                 * Setting standard timezone offset
                 */
                $timezoneOffsetResponse->setStandardTimezoneOffset(
                    $timezoneOffset
                );
            } else {

                /**
                 * Setting dst timezone offset
                 */
                $timezoneOffsetResponse->setDstTimezoneOffset(
                    $timezoneOffset
                );
            }
        }

        return $timezoneOffsetResponse;
    }

    /**
     * @param float $latitude
     * @param float $longitude
     *
     * @return Timezone
     *
     * @throws BaseException
     * @throws DatabaseException
     * @throws GoogleTimeZoneException
     * @throws TranslateException
     */
    public function getByCoordinates(
        float $latitude,
        float $longitude
    ) : Timezone
    {
        /**
         * Getting google timezone response
         */
        $googleTimezoneResponse = $this->googleTimezoneService->getByCoordinates(
            $latitude,
            $longitude,
            Carbon::now()->timestamp
        );

        return $this->getOrCreate(
            $googleTimezoneResponse->timeZoneId,
            $latitude,
            $longitude
        );
    }
}
