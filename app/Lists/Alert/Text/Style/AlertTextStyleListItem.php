<?php

namespace App\Lists\Alert\Text\Style;

/**
 * Class AlertTextStyleListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 *
 * @package App\Lists\Alert\Text\Style
 */
class AlertTextStyleListItem
{
    /**
     * @var int
     */
    public int $id;

    /**
     * @var string
     */
    public string $code;

    /**
     * @var string
     */
    public string $name;

    /**
     * AlertTextStyleListItem constructor
     *
     * @param int $id
     * @param string $code
     * @param string $name
     */
    public function __construct(
        int $id,
        string $code,
        string $name
    )
    {
        /** @var int id */
        $this->id = $id;

        /** @var string code */
        $this->code = $code;

        /** @var string name */
        $this->name = $name;
    }

    /**
     * @return bool
     */
    public function isRegular() : bool
    {
        return $this->code == 'regular';
    }

    /**
     * @return bool
     */
    public function isMedium() : bool
    {
        return $this->code == 'medium';
    }

    /**
     * @return bool
     */
    public function isSemibold() : bool
    {
        return $this->code == 'semibold';
    }

    /**
     * @return bool
     */
    public function isBold() : bool
    {
        return $this->code == 'bold';
    }
}