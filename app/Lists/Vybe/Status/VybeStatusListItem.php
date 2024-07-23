<?php

namespace App\Lists\Vybe\Status;

/**
 * Class VybeStatusListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $count
 *
 * @package App\Lists\Vybe\Status
 */
class VybeStatusListItem
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
     * @var int|null
     */
    public ?int $count;

    /**
     * VybeStatusListItem constructor
     *
     * @param int $id
     * @param string $code
     * @param string $name
     * @param int|null $count
     */
    public function __construct(
        int $id,
        string $code,
        string $name,
        ?int $count = null
    )
    {
        /** @var int id */
        $this->id = $id;

        /** @var string code */
        $this->code = $code;

        /** @var string name */
        $this->name = $name;

        /** @var int count */
        $this->count = $count;
    }

    /**
     * @return bool
     */
    public function isDraft() : bool
    {
        return $this->code == 'draft';
    }

    /**
     * @return bool
     */
    public function isPublished() : bool
    {
        return $this->code == 'published';
    }

    /**
     * @return bool
     */
    public function isPaused() : bool
    {
        return $this->code == 'paused';
    }

    /**
     * @return bool
     */
    public function isSuspended() : bool
    {
        return $this->code == 'suspended';
    }

    /**
     * @return bool
     */
    public function isDeleted() : bool
    {
        return $this->code == 'deleted';
    }

    /**
     * @return int|null
     */
    public function getCount() : ?int
    {
        return $this->count;
    }

    /**
     * @param int|null $count
     *
     * @return void
     */
    public function setCount(
        ?int $count
    ) : void
    {
        $this->count = $count ?: 0;
    }
}