<?php

namespace App\Lists\Alert\Align;

/**
 * Class AlertAlignListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 *
 * @package App\Lists\Alert\Align
 */
class AlertAlignListItem
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
     * AlertAlignListItem constructor
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
    public function isLeft() : bool
    {
        return $this->code == 'left';
    }

    /**
     * @return bool
     */
    public function isCenter() : bool
    {
        return $this->code == 'center';
    }

    /**
     * @return bool
     */
    public function isRight() : bool
    {
        return $this->code == 'right';
    }
}