<?php

namespace App\Lists\Payment\Method\Payment\Status;

/**
 * Class PaymentStatusListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 *
 * @package App\Lists\Payment\Method\Payment\Status
 */
class PaymentStatusListItem
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
     * PaymentStatusListItem constructor
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
    public function isActive() : bool
    {
        return $this->code == 'active';
    }

    /**
     * @return bool
     */
    public function isInactive() : bool
    {
        return $this->code == 'inactive';
    }

    /**
     * @return bool
     */
    public function isPaused() : bool
    {
        return $this->code == 'paused';
    }
}