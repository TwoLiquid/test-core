<?php

namespace App\Lists\Alert\Animation;

/**
 * Class AlertAnimationListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 *
 * @package App\Lists\Alert\Animation
 */
class AlertAnimationListItem
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
     * AlertAnimationListItem constructor
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
    public function isFlip() : bool
    {
        return $this->code == 'flip';
    }

    /**
     * @return bool
     */
    public function isBounce() : bool
    {
        return $this->code == 'bounce';
    }

    /**
     * @return bool
     */
    public function isFlash() : bool
    {
        return $this->code == 'flash';
    }

    /**
     * @return bool
     */
    public function isPulse() : bool
    {
        return $this->code == 'pulse';
    }

    /**
     * @return bool
     */
    public function isRubberBand() : bool
    {
        return $this->code == 'rubber_band';
    }

    /**
     * @return bool
     */
    public function isShakeX() : bool
    {
        return $this->code == 'shake_x';
    }

    /**
     * @return bool
     */
    public function isShakeY() : bool
    {
        return $this->code == 'shake_y';
    }

    /**
     * @return bool
     */
    public function isHeadShake() : bool
    {
        return $this->code == 'head_shake';
    }

    /**
     * @return bool
     */
    public function isSwing() : bool
    {
        return $this->code == 'swing';
    }

    /**
     * @return bool
     */
    public function isTaDa() : bool
    {
        return $this->code == 'ta_da';
    }

    /**
     * @return bool
     */
    public function isWobble() : bool
    {
        return $this->code == 'wobble';
    }

    /**
     * @return bool
     */
    public function isJello() : bool
    {
        return $this->code == 'jello';
    }

    /**
     * @return bool
     */
    public function isHeartBeat() : bool
    {
        return $this->code == 'heart_beat';
    }
}