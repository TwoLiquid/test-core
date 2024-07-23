<?php

namespace App\Lists\ToastMessage\Type;

/**
 * Class ToastMessageTypeListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 *
 * @package App\Lists\ToastMessage\Type
 */
class ToastMessageTypeListItem
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
     * ToastMessageTypeListItem constructor
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
    public function isSubmitted() : bool
    {
        return $this->code == 'submitted';
    }

    /**
     * @return bool
     */
    public function isCanceled() : bool
    {
        return $this->code == 'canceled';
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
    public function isDeclined() : bool
    {
        return $this->code == 'declined';
    }

    /**
     * @return bool
     */
    public function isDeletion() : bool
    {
        return $this->code == 'deletion';
    }

    /**
     * @return bool
     */
    public function isWarning() : bool
    {
        return $this->code == 'warning';
    }
}