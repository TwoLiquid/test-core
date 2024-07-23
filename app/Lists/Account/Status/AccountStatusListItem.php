<?php

namespace App\Lists\Account\Status;

/**
 * Class AccountStatusListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 *
 * @package App\Lists\Account\Status
 */
class AccountStatusListItem
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
     * AccountStatusListItem constructor
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
    public function isPending() : bool
    {
        return $this->code == 'pending';
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
    public function isSuspended() : bool
    {
        return $this->code == 'suspended';
    }

    /**
     * @return bool
     */
    public function isDeactivated() : bool
    {
        return $this->code == 'deactivated';
    }

    /**
     * @return bool
     */
    public function isDeleted() : bool
    {
        return $this->code == 'deleted';
    }
}