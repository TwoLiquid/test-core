<?php

namespace App\Lists\Request\Field\Status;

/**
 * Class RequestFieldStatusListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 *
 * @package App\Lists\Request\Field\Status
 */
class RequestFieldStatusListItem
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
     * RequestFieldStatusListItem constructor
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
    public function isPending() : bool
    {
        return $this->code == 'pending';
    }

    /**
     * @return bool
     */
    public function isDeclined() : bool
    {
        return $this->code == 'declined';
    }

    /**
     * @return bool
     */
    public function isAccepted() : bool
    {
        return $this->code == 'accepted';
    }

    /**
     * @return bool
     */
    public function isCanceled() : bool
    {
        return $this->code == 'canceled';
    }
}