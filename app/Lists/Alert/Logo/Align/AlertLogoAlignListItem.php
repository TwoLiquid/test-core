<?php

namespace App\Lists\Alert\Logo\Align;

/**
 * Class AlertLogoAlignListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 *
 * @package App\Lists\Alert\Logo\Align
 */
class AlertLogoAlignListItem
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
     * AlertLogoAlignListItem constructor
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
    public function isBottomCenter() : bool
    {
        return $this->code == 'bottom_center';
    }

    /**
     * @return bool
     */
    public function isTopCenter() : bool
    {
        return $this->code == 'top_center';
    }
}