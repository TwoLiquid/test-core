<?php

namespace App\Lists\User\State\Status;

/**
 * Class UserStateStatusListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 *
 * @package App\Lists\User\State\Status
 */
class UserStateStatusListItem
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
     * UserStateStatusListItem constructor
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
    public function isOnline() : bool
    {
        return $this->code == 'online';
    }

    /**
     * @return bool
     */
    public function isIdle() : bool
    {
        return $this->code == 'idle';
    }

    /**
     * @return bool
     */
    public function isOffline() : bool
    {
        return $this->code == 'offline';
    }

    /**
     * @return bool
     */
    public function isInvisible() : bool
    {
        return $this->code == 'invisible';
    }
}