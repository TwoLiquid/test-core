<?php

namespace App\Models\MySql\Admin;

use App\Lists\Admin\Status\AdminStatusList;
use App\Lists\Admin\Status\AdminStatusListItem;
use App\Models\MySql\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Eloquent;

/**
 * App\Models\MySql\Admin\Admin
 *
 * @property int $id
 * @property int $auth_id
 * @property int $status_id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property bool $full_access
 * @property bool $initial_password
 * @property string|null $password_reset_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read AdminAuthProtection|null $authProtection
 * @property-read string $full_id
 * @property-read DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection<int, Role> $roles
 * @property-read int|null $roles_count
 * @method static Builder|Admin newModelQuery()
 * @method static Builder|Admin newQuery()
 * @method static Builder|Admin onlyTrashed()
 * @method static Builder|Admin query()
 * @method static Builder|Admin whereAuthId($value)
 * @method static Builder|Admin whereCreatedAt($value)
 * @method static Builder|Admin whereDeletedAt($value)
 * @method static Builder|Admin whereEmail($value)
 * @method static Builder|Admin whereFirstName($value)
 * @method static Builder|Admin whereFullAccess($value)
 * @method static Builder|Admin whereId($value)
 * @method static Builder|Admin whereInitialPassword($value)
 * @method static Builder|Admin whereLastName($value)
 * @method static Builder|Admin wherePasswordResetToken($value)
 * @method static Builder|Admin whereStatusId($value)
 * @method static Builder|Admin whereUpdatedAt($value)
 * @method static Builder|Admin withTrashed()
 * @method static Builder|Admin withoutTrashed()
 * @mixin Eloquent
 */
class Admin extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * Database connection type
     *
     * @var string
     */
    protected $connection = 'mysql';

    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'admins';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'auth_id', 'status_id', 'first_name', 'last_name', 'email',
        'full_access', 'initial_password', 'password_reset_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'full_access'      => 'boolean',
        'initial_password' => 'boolean'
    ];

    //--------------------------------------------------------------------------
    // Methods

    /**
     * @return bool
     */
    public function hasFullAccess() : bool
    {
        return $this->full_access;
    }

    /**
     * @return bool
     */
    public function isInitialPassword() : bool
    {
        return $this->initial_password;
    }

    /**
     * @return bool
     */
    public function hasAuthProtection() : bool
    {
        if ($this->authProtection) {
            return true;
        }

        return false;
    }

    //--------------------------------------------------------------------------
    // Belongs to many relations

    /**
     * @return BelongsToMany
     */
    public function roles() : BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    //--------------------------------------------------------------------------
    // Has one relations

    /**
     * @return HasOne
     */
    public function authProtection() : HasOne
    {
        return $this->hasOne(AdminAuthProtection::class);
    }

    //--------------------------------------------------------------------------
    // Lists accessors

    /**
     * @return AdminStatusListItem|null
     */
    public function getStatus() : ?AdminStatusListItem
    {
        return AdminStatusList::getItem(
            $this->status_id
        );
    }

    //--------------------------------------------------------------------------
    // Accessors

    /**
     * @return string
     */
    public function getFullIdAttribute() : string
    {
        return 'A' . $this->id;
    }
}
