<?php

namespace App\Lists\Permission;

/**
 * Class PermissionListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property bool $selected
 *
 * @package App\Lists\Permission
 */
class PermissionListItem
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
     * @var bool|null
     */
    public ?bool $selected;

    /**
     * PermissionListItem constructor
     *
     * @param int $id
     * @param string $code
     * @param string $name
     * @param bool|null $selected
     */
    public function __construct(
        int $id,
        string $code,
        string $name,
        bool $selected = null
    )
    {
        /** @var int id */
        $this->id = $id;

        /** @var string code */
        $this->code = $code;

        /** @var string name */
        $this->name = $name;

        /** @var bool selected */
        $this->selected = $selected;
    }

    /**
     * @return bool
     */
    public function isView() : bool
    {
        return $this->code == 'view';
    }

    /**
     * @return bool
     */
    public function isEdit() : bool
    {
        return $this->code == 'edit';
    }

    /**
     * @return bool
     */
    public function isAdd() : bool
    {
        return $this->code == 'add';
    }

    /**
     * @return bool
     */
    public function isDelete() : bool
    {
        return $this->code == 'delete';
    }

    /**
     * @return bool|null
     */
    public function getSelected() : ?bool
    {
        return $this->selected;
    }

    /**
     * @param bool $selected
     *
     * @return void
     */
    public function setSelected(
        bool $selected
    ) : void
    {
        $this->selected = $selected;
    }
}