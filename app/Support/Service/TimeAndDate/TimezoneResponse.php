<?php

namespace App\Support\Service\TimeAndDate;

/**
 * Class TimezoneResponse
 *
 * @property TimezoneResponse $childTimezone
 * @property string $code
 * @property string $name
 * @property int $dstOffset
 * @property int $rawOffset
 * @property int $totalOffset
 *
 * @package App\Support\Service\TimeAndDate
 */
class TimezoneResponse
{
    /**
     * @var TimezoneResponse|null
     */
    public ?TimezoneResponse $childTimezone = null;

    /**
     * @var string
     */
    public string $code;

    /**
     * @var string|null
     */
    public ?string $name;

    /**
     * @var int
     */
    public int $dstOffset;

    /**
     * @var int
     */
    public int $rawOffset;

    /**
     * @var int
     */
    public int $totalOffset;

    /**
     * TimezoneResponse constructor
     *
     * @param string $code
     * @param string|null $name
     * @param int $dstOffset
     * @param int $rawOffset
     * @param int $totalOffset
     */
    public function __construct(
        string $code,
        ?string $name,
        int $dstOffset,
        int $rawOffset,
        int $totalOffset
    )
    {
        $this->code = $code;
        $this->name = $name;
        $this->dstOffset = $dstOffset;
        $this->rawOffset = $rawOffset;
        $this->totalOffset = $totalOffset;
    }

    /**
     * @return TimezoneResponse|null
     */
    public function getChildTimezone() : ?TimezoneResponse
    {
        return $this->childTimezone;
    }

    /**
     * @param TimezoneResponse|null $timezoneResponse
     */
    public function setChildTimezone(
        ?TimezoneResponse $timezoneResponse
    ) : void
    {
        $this->childTimezone = $timezoneResponse;
    }
}