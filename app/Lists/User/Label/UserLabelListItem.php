<?php

namespace App\Lists\User\Label;

/**
 * Class UserLabelListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $comment
 *
 * @package App\Lists\User\Label
 */
class UserLabelListItem
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
     * @var string|null
     */
    public ?string $comment;

    /**
     * UserLabelListItem constructor
     *
     * @param int $id
     * @param string $code
     * @param string $name
     * @param string|null $comment
     */
    public function __construct(
        int $id,
        string $code,
        string $name,
        ?string $comment
    )
    {
        /** @var int id */
        $this->id = $id;

        /** @var string code */
        $this->code = $code;

        /** @var string name */
        $this->name = $name;

        /** @var string comment */
        $this->comment = $comment;
    }

    /**
     * @return bool
     */
    public function isStreamer() : bool
    {
        return $this->code == 'streamer';
    }

    /**
     * @return bool
     */
    public function isDeactivate() : bool
    {
        return $this->code == 'deactivate';
    }

    /**
     * @return bool
     */
    public function isBlocked() : bool
    {
        return $this->code == 'blocked';
    }

    /**
     * @return bool
     */
    public function isCreator() : bool
    {
        return $this->code == 'creator';
    }

    /**
     * @return bool
     */
    public function isSuspended() : bool
    {
        return $this->code == 'suspended';
    }
}