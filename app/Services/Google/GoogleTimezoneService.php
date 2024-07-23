<?php

namespace App\Services\Google;

use App\Exceptions\BaseException;
use App\Services\Google\Interfaces\GoogleTimezoneServiceInterface;
use App\Support\Service\Google\Timezone\GoogleTimezoneResponse;
use Spatie\GoogleTimeZone\Exceptions\GoogleTimeZoneException;
use Spatie\GoogleTimeZone\GoogleTimeZone;
use DateTime;

/**
 * Class GoogleTimezoneService
 *
 * @package App\Services\Google
 */
class GoogleTimezoneService implements GoogleTimezoneServiceInterface
{
    /**
     * Google time zone
     *
     * @var GoogleTimeZone
     */
    protected GoogleTimeZone $googleTimezone;

    /**
     * GoogleTimezoneService constructor
     */
    public function __construct()
    {
        /** @var GoogleTimeZone googleTimezone */
        $this->googleTimezone = new GoogleTimeZone();

        /**
         * Setting api key
         */
        $this->googleTimezone->setApiKey(
            config('google-time-zone.key')
        );

        /**
         * Setting default language
         */
        $this->googleTimezone->setLanguage(
            config('google-time-zone.language')
        );
    }

    /**
     * @param float $latitude
     * @param float $longitude
     * @param string $timestamp
     *
     * @return GoogleTimezoneResponse
     *
     * @throws BaseException
     * @throws GoogleTimeZoneException
     */
    public function getByCoordinates(
        float $latitude,
        float $longitude,
        string $timestamp
    ) : GoogleTimezoneResponse
    {
        /**
         * Setting google timezone timestamp
         */
        $this->googleTimezone->setTimestamp(
            (new DateTime())->setTimestamp(
                $timestamp
            )
        );

        /**
         * Getting Google Timezone for coordinates
         */
        $response = $this->googleTimezone->getTimeZoneForCoordinates(
            $latitude,
            $longitude
        );

        if (isset($response['status']) && $response['status'] == 'ZERO_RESULTS') {
            throw new BaseException(
                'Getting timezone by coordinates error.',
                null,
                404
            );
        }

        return new GoogleTimezoneResponse(
            $response['dstOffset'],
            $response['rawOffset'],
            $response['timeZoneId'],
            $response['timeZoneName']
        );
    }

    /**
     * @param float $latitude
     * @param float $longitude
     * @param string $timestamp
     *
     * @return bool
     *
     * @throws BaseException
     * @throws GoogleTimeZoneException
     */
    public function checkDst(
        float $latitude,
        float $longitude,
        string $timestamp
    ) : bool
    {
        /**
         * Setting google timezone timestamp
         */
        $this->googleTimezone->setTimestamp(
            (new DateTime())->setTimestamp(
                $timestamp
            )
        );

        /**
         * Getting Google Timezone for coordinates
         */
        $response = $this->googleTimezone->getTimeZoneForCoordinates(
            $latitude,
            $longitude
        );

        if (isset($response['status']) && $response['status'] == 'ZERO_RESULTS') {
            throw new BaseException(
                'Checking timezone is in DST error.',
                null,
                404
            );
        }

        return $response['dstOffset'] > 0;
    }
}
