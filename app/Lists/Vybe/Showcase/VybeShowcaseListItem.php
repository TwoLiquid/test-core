<?php

namespace App\Lists\Vybe\Showcase;

/**
 * Class VybeShowcaseListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 *
 * @package App\Lists\Vybe\Showcase
 */
class VybeShowcaseListItem
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
     * VybeShowcaseListItem constructor
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
    public function isProfileAndCatalogs() : bool
    {
        return $this->code == 'profile_and_catalogs';
    }

    /**
     * @return bool
     */
    public function isOnlyProfile() : bool
    {
        return $this->code == 'only_profile';
    }
}