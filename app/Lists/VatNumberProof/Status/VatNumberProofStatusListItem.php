<?php

namespace App\Lists\VatNumberProof\Status;

/**
 * Class VatNumberProofStatusListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 *
 * @package App\Lists\VatNumberProof\Status
 */
class VatNumberProofStatusListItem
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
     * VatNumberProofStatusListItem constructor
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
    public function isInactive() : bool
    {
        return $this->code == 'inactive';
    }

    /**
     * @return bool
     */
    public function isActive() : bool
    {
        return $this->code == 'active';
    }
}