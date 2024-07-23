<?php

namespace App\Lists\Vybe\Period;

/**
 * Class VybePeriodListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 *
 * @package App\Lists\Vybe\Period
 */
class VybePeriodListItem
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
     * VybePeriodListItem constructor
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
    public function isOngoing() : bool
    {
        return $this->code == 'ongoing';
    }

    /**
     * @return bool
     */
    public function isOneTime() : bool
    {
        return $this->code == 'one-time';
    }
}