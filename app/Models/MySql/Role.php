<?php

namespace App\Models\MySql;

use App\Models\MySql\Admin\Admin;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Eloquent;

/**
 * App\Models\MySql\Role
 *
 * @property int $id
 * @property string $name
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, Admin> $admins
 * @property-read int|null $admins_count
 * @property-read Collection<int, Permission> $permissions
 * @property-read int|null $permissions_count
 * @method static Builder|Role newModelQuery()
 * @method static Builder|Role newQuery()
 * @method static Builder|Role onlyTrashed()
 * @method static Builder|Role query()
 * @method static Builder|Role whereCreatedAt($value)
 * @method static Builder|Role whereDeletedAt($value)
 * @method static Builder|Role whereId($value)
 * @method static Builder|Role whereName($value)
 * @method static Builder|Role whereUpdatedAt($value)
 * @method static Builder|Role withTrashed()
 * @method static Builder|Role withoutTrashed()
 * @mixin Eloquent
 */
class Role extends Model
{
    use SoftDeletes;

    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'roles';

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
        'name'
    ];

    //--------------------------------------------------------------------------
    // Belongs to many relations

    /**
     * @return BelongsToMany
     */
    public function admins() : BelongsToMany
    {
        return $this->belongsToMany(Admin::class);
    }

    //--------------------------------------------------------------------------
    // Has many relations

    /**
     * @return HasMany
     */
    public function permissions() : HasMany
    {
        return $this->hasMany(Permission::class);
    }
}
