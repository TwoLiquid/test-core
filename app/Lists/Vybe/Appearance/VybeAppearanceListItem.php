<?php

namespace App\Lists\Vybe\Appearance;

/**
 * Class VybeAppearanceListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 *
 * @package App\Lists\Vybe\Appearance
 */
class VybeAppearanceListItem
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
     * VybeAppearanceListItem constructor
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
    public function isVoiceChat() : bool
    {
        return $this->code == 'voice_chat';
    }

    /**
     * @return bool
     */
    public function isVideoChat() : bool
    {
        return $this->code == 'video_chat';
    }

    /**
     * @return bool
     */
    public function isRealLife() : bool
    {
        return $this->code == 'real_life';
    }
}