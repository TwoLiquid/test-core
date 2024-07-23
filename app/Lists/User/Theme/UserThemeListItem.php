<?php

namespace App\Lists\User\Theme;

/**
 * Class UserThemeListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 *
 * @package App\Lists\User\Theme
 */
class UserThemeListItem
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
     * UserThemeListItem constructor
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
    public function isDark() : bool
    {
        return $this->code == 'dark';
    }

    /**
     * @return bool
     */
    public function isLight() : bool
    {
        return $this->code == 'light';
    }
}
