<?php

namespace App\Lists\Vybe\Access;

/**
 * Class VybeAccessListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 *
 * @package App\Lists\Vybe\Access
 */
class VybeAccessListItem
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
     * VybeAccessListItem constructor
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
    public function isPublic() : bool
    {
        return $this->code == 'public';
    }

    /**
     * @return bool
     */
    public function isPrivate() : bool
    {
        return $this->code == 'private';
    }
}