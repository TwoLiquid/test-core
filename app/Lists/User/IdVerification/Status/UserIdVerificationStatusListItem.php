<?php

namespace App\Lists\User\IdVerification\Status;

/**
 * Class UserIdVerificationStatusListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 *
 * @package App\Lists\User\IdVerification\Status
 */
class UserIdVerificationStatusListItem
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
     * UserIdVerificationStatusListItem constructor
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
    public function isUnverified() : bool
    {
        return $this->code == 'unverified';
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
    public function isVerified() : bool
    {
        return $this->code == 'verified';
    }
}