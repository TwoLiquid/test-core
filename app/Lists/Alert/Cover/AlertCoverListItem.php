<?php

namespace App\Lists\Alert\Cover;

/**
 * Class AlertCoverListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 *
 * @package App\Lists\Alert\Cover
 */
class AlertCoverListItem
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
     * AlertCoverListItem constructor
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
    public function isNone() : bool
    {
        return $this->code == 'none';
    }

    /**
     * @return bool
     */
    public function isGradientStringCover() : bool
    {
        return $this->code == 'gradient_string_cover';
    }

    /**
     * @return bool
     */
    public function isGradientBoxCover() : bool
    {
        return $this->code == 'gradient_box_cover';
    }

    /**
     * @return bool
     */
    public function isSolidBoxCover() : bool
    {
        return $this->code == 'solid_box_cover';
    }
}