<?php

namespace App\Models\MySql;

use App\Lists\Permission\PermissionList;
use App\Lists\Permission\PermissionListItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Eloquent;

/**
 * App\Models\MySql\Permission
 *
 * @property int $id
 * @property int $role_id
 * @property int $permission_id
 * @property string $department_code
 * @property string $page_code
 * @property bool $selected
 * @property-read Role $role
 * @method static Builder|Permission newModelQuery()
 * @method static Builder|Permission newQuery()
 * @method static Builder|Permission query()
 * @method static Builder|Permission whereDepartmentCode($value)
 * @method static Builder|Permission whereId($value)
 * @method static Builder|Permission wherePageCode($value)
 * @method static Builder|Permission wherePermissionId($value)
 * @method static Builder|Permission whereRoleId($value)
 * @method static Builder|Permission whereSelected($value)
 * @mixin Eloquent
 */
class Permission extends Model
{
    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'permissions';

    /**
     * Provide default timestamps usage
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id', 'permission_id', 'department_code',
        'page_code', 'selected'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'selected' => 'boolean'
    ];

    //--------------------------------------------------------------------------
    // Belongs to relations

    /**
     * @return BelongsTo
     */
    public function role() : BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    //--------------------------------------------------------------------------
    // Lists accessors

    /**
     * @return PermissionListItem|null
     */
    public function getPermission() : ?PermissionListItem
    {
        return PermissionList::getItem(
            $this->permission_id
        );
    }
}
