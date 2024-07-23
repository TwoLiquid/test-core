<?php

namespace App\Lists\Unit\Type;

/**
 * Class UnitTypeListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 *
 * @package App\Lists\Unit\Type
 */
class UnitTypeListItem
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
     * UnitTypeListItem constructor
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
    public function isUsual() : bool
    {
        return $this->code == 'usual';
    }

    /**
     * @return bool
     */
    public function isEvent() : bool
    {
        return $this->code == 'event';
    }
}