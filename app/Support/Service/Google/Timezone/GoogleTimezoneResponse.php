<?php

namespace App\Support\Service\Google\Timezone;

/**
 * Class TimezoneResponse
 *
 * @property int $dstOffset
 * @property int $rawOffset
 * @property string $timeZoneId
 * @property string $timeZoneName
 *
 * @package App\Support\Service\Google\Timezone
 */
class GoogleTimezoneResponse
{
    /**
     * @var int
     */
    public int $dstOffset;

    /**
     * @var int
     */
    public int $rawOffset;

    /**
     * @var string
     */
    public string $timeZoneId;

    /**
     * @var string
     */
    public string $timeZoneName;

    /**
     * GoogleTimezoneResponse constructor
     *
     * @param int $dstOffset
     * @param int $rawOffset
     * @param string $timeZoneId
     * @param string $timeZoneName
     */
    public function __construct(
        int $dstOffset,
        int $rawOffset,
        string $timeZoneId,
        string $timeZoneName
    )
    {
        $this->dstOffset = $dstOffset;
        $this->rawOffset = $rawOffset;
        $this->timeZoneId = $timeZoneId;
        $this->timeZoneName = $timeZoneName;
    }
}