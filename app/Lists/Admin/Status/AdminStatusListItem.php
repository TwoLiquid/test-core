<?php

namespace App\Lists\Admin\Status;

/**
 * Class AdminStatusListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 *
 * @package App\Lists\Admin\Status
 */
class AdminStatusListItem
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
     * AdminStatusListItem constructor
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
    public function isActive() : bool
    {
        return $this->code == 'active';
    }

    /**
     * @return bool
     */
    public function isDisable() : bool
    {
        return $this->code == 'disable';
    }
}