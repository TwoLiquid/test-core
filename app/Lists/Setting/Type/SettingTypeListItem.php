<?php

namespace App\Lists\Setting\Type;

/**
 * Class SettingTypeListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 *
 * @package App\Lists\Setting\Type
 */
class SettingTypeListItem
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
     * SettingTypeListItem constructor
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
    public function isDefault() : bool
    {
        return $this->code == 'default';
    }

    /**
     * @return bool
     */
    public function isCustom() : bool
    {
        return $this->code == 'custom';
    }
}