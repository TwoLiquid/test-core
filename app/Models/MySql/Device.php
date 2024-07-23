<?php

namespace App\Models\MySql;

use App\Models\MySql\Activity\Activity;
use App\Models\MySql\Vybe\Vybe;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Eloquent;

/**
 * App\Models\MySql\Device
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property bool $visible
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 * @property-read Collection<int, Vybe> $vybes
 * @property-read int|null $vybes_count
 * @method static Builder|Device newModelQuery()
 * @method static Builder|Device newQuery()
 * @method static Builder|Device onlyTrashed()
 * @method static Builder|Device query()
 * @method static Builder|Device whereCode($value)
 * @method static Builder|Device whereDeletedAt($value)
 * @method static Builder|Device whereId($value)
 * @method static Builder|Device whereName($value)
 * @method static Builder|Device whereVisible($value)
 * @method static Builder|Device withTrashed()
 * @method static Builder|Device withoutTrashed()
 * @mixin Eloquent
 */
class Device extends Model
{
    use SoftDeletes;

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
    protected $table = 'devices';

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
        'name', 'code', 'visible'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'visible' => 'boolean'
    ];

    //--------------------------------------------------------------------------
    // Belongs to many relations

    /**
     * @return BelongsToMany
     */
    public function vybes() : BelongsToMany
    {
        return $this->belongsToMany(
            Vybe::class,
            'device_vybe',
            'device_id',
            'vybe_id'
        );
    }

    /**
     * @return BelongsToMany
     */
    public function activities() : BelongsToMany
    {
        return $this->belongsToMany(
            Activity::class
        );
    }
}
