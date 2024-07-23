<?php

namespace App\Models\MySql\Admin;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Eloquent;

/**
 * App\Models\MySql\Admin\AdminAuthProtection
 *
 * @property int $id
 * @property int $admin_id
 * @property string $secret
 * @property Carbon $added_at
 * @property-read Admin $admin
 * @method static Builder|AdminAuthProtection newModelQuery()
 * @method static Builder|AdminAuthProtection newQuery()
 * @method static Builder|AdminAuthProtection query()
 * @method static Builder|AdminAuthProtection whereAddedAt($value)
 * @method static Builder|AdminAuthProtection whereAdminId($value)
 * @method static Builder|AdminAuthProtection whereId($value)
 * @method static Builder|AdminAuthProtection whereSecret($value)
 * @mixin Eloquent
 */
class AdminAuthProtection extends Model
{
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
    protected $table = 'admin_auth_protections';

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
        'admin_id', 'secret', 'added_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'secret'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'added_at' => 'datetime'
    ];

    //--------------------------------------------------------------------------
    // Has one relations

    /**
     * @return BelongsTo
     */
    public function admin() : BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    //--------------------------------------------------------------------------
    // Lists accessors

    /**
     * @param string $value
     */
    public function setSecretAttribute(
        string $value
    ) : void
    {
        $this->attributes['secret'] = encrypt($value);
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function getSecretAttribute(
        string $value
    ) : string
    {
        return decrypt($value);
    }
}
