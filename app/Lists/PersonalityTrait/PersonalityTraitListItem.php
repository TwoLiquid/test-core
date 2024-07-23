<?php

namespace App\Lists\PersonalityTrait;

/**
 * Class PersonalityTraitListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 *
 * @package App\Lists\PersonalityTrait
 */
class PersonalityTraitListItem
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
     * LanguageListItem constructor
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
    public function isFriendly() : bool
    {
        return $this->code == 'friendly';
    }

    /**
     * @return bool
     */
    public function isConfident() : bool
    {
        return $this->code == 'confident';
    }

    /**
     * @return bool
     */
    public function isSociable() : bool
    {
        return $this->code == 'sociable';
    }

    /**
     * @return bool
     */
    public function isAssertive() : bool
    {
        return $this->code == 'assertive';
    }

    /**
     * @return bool
     */
    public function isOutgoing() : bool
    {
        return $this->code == 'outgoing';
    }

    /**
     * @return bool
     */
    public function isEnergetic() : bool
    {
        return $this->code == 'energetic';
    }

    /**
     * @return bool
     */
    public function isTalkative() : bool
    {
        return $this->code == 'talkative';
    }

    /**
     * @return bool
     */
    public function isArticulate() : bool
    {
        return $this->code == 'articulate';
    }

    /**
     * @return bool
     */
    public function isAffectionate() : bool
    {
        return $this->code == 'affectionate';
    }
}