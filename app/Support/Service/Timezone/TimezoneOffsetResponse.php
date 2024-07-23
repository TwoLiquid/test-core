<?php

namespace App\Support\Service\Timezone;

use App\Models\MySql\Timezone\TimezoneOffset;

/**
 * Class TimezoneOffsetResponse
 *
 * @property TimezoneOffset $standardTimezoneOffset
 * @property TimezoneOffset $dstTimezoneOffset
 *
 * @package App\Support\Service\Timezone
 */
class TimezoneOffsetResponse
{
    /**
     * @var TimezoneOffset|null
     */
    public ?TimezoneOffset $standardTimezoneOffset = null;

    /**
     * @var TimezoneOffset|null
     */
    public ?TimezoneOffset $dstTimezoneOffset = null;

    /**
     * @return TimezoneOffset|null
     */
    public function getStandardTimezoneOffset() : ?TimezoneOffset
    {
        return $this->standardTimezoneOffset;
    }

    /**
     * @param TimezoneOffset|null $standardTimezoneOffset
     *
     * @return void
     */
    public function setStandardTimezoneOffset(
        ?TimezoneOffset $standardTimezoneOffset
    ) : void
    {
        /** @var TimezoneOffset standardTimezoneOffset */
        $this->standardTimezoneOffset = $standardTimezoneOffset;
    }

    /**
     * @return TimezoneOffset|null
     */
    public function getDstTimezoneOffset() : ?TimezoneOffset
    {
        return $this->dstTimezoneOffset;
    }

    /**
     * @param TimezoneOffset|null $dstTimezoneOffset
     *
     * @return void
     */
    public function setDstTimezoneOffset(
        ?TimezoneOffset $dstTimezoneOffset
    ) : void
    {
        /** @var TimezoneOffset dstTimezoneOffset */
        $this->dstTimezoneOffset = $dstTimezoneOffset;
    }
}
