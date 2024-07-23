<?php

namespace App\Lists\Alert\Type;

/**
 * Class AlertTypeListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 *
 * @package App\Lists\Alert\Type
 */
class AlertTypeListItem
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
     * AlertTypeListItem constructor
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
    public function isOrder() : bool
    {
        return $this->code == 'order';
    }

    /**
     * @return bool
     */
    public function isTipping() : bool
    {
        return $this->code == 'tipping';
    }
}