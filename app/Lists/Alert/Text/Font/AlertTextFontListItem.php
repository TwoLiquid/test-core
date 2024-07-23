<?php

namespace App\Lists\Alert\Text\Font;

/**
 * Class AlertTextFontListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 *
 * @package App\Lists\Alert\Text\Font
 */
class AlertTextFontListItem
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
     * AlertTextFontListItem constructor
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
    public function isMontserrat() : bool
    {
        return $this->code == 'montserrat';
    }

    /**
     * @return bool
     */
    public function isInter() : bool
    {
        return $this->code == 'inter';
    }

    /**
     * @return bool
     */
    public function isOswald() : bool
    {
        return $this->code == 'oswald';
    }

    /**
     * @return bool
     */
    public function isRaleway() : bool
    {
        return $this->code == 'raleway';
    }

    /**
     * @return bool
     */
    public function isPlayfairDisplay() : bool
    {
        return $this->code == 'playfair_display';
    }

    /**
     * @return bool
     */
    public function isComfortaa() : bool
    {
        return $this->code == 'comfortaa';
    }

    /**
     * @return bool
     */
    public function isCaveat() : bool
    {
        return $this->code == 'caveat';
    }

    /**
     * @return bool
     */
    public function isJura() : bool
    {
        return $this->code == 'jura';
    }

    /**
     * @return bool
     */
    public function isVollkorn() : bool
    {
        return $this->code == 'vollkorn';
    }
}