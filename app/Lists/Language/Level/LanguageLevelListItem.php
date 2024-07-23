<?php

namespace App\Lists\Language\Level;

/**
 * Class LanguageLevelListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 *
 * @package App\Lists\Language\Level
 */
class LanguageLevelListItem
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
     * LanguageLevelListItem constructor
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
    public function isBasic() : bool
    {
        return $this->code == 'basic';
    }

    /**
     * @return bool
     */
    public function isProficient() : bool
    {
        return $this->code == 'proficient';
    }

    /**
     * @return bool
     */
    public function isFluent() : bool
    {
        return $this->code == 'fluent';
    }

    /**
     * @return bool
     */
    public function isNative() : bool
    {
        return $this->code == 'native';
    }
}