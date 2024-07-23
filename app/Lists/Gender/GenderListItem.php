<?php

namespace App\Lists\Gender;

/**
 * Class GenderListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 *
 * @package App\Lists\Gender
 */
class GenderListItem
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
     * GenderListItem constructor
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
    public function isMale() : bool
    {
        return $this->code == 'male';
    }

    /**
     * @return bool
     */
    public function isFemale() : bool
    {
        return $this->code == 'female';
    }

    /**
     * @return bool
     */
    public function isOther() : bool
    {
        return $this->code == 'other';
    }
}