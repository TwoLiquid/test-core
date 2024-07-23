<?php

namespace App\Lists\Vybe\OrderAccept;

/**
 * Class VybeOrderAcceptListItem
 * 
 * @property int id
 * @property string code
 * @property string name
 * 
 * @package App\Lists\Vybe\OrderAccept
 */
class VybeOrderAcceptListItem
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
     * VybeOrderAcceptListItem constructor
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
    public function isAuto() : bool
    {
        return $this->code == 'auto';
    }

    /**
     * @return bool
     */
    public function isManual() : bool
    {
        return $this->code == 'manual';
    }
}
