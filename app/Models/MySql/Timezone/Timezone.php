<?php

namespace App\Models\MySql\Timezone;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Eloquent;

/**
 * App\Models\MySql\Timezone\Timezone
 *
 * @property int $id
 * @property string $external_id
 * @property bool $has_dst
 * @property bool $in_dst
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, TimezoneOffset> $offsets
 * @property-read int|null $offsets_count
 * @property-read Collection<int, TimezoneTimeChange> $timeChanges
 * @property-read int|null $time_changes_count
 * @method static Builder|Timezone newModelQuery()
 * @method static Builder|Timezone newQuery()
 * @method static Builder|Timezone onlyTrashed()
 * @method static Builder|Timezone query()
 * @method static Builder|Timezone whereDeletedAt($value)
 * @method static Builder|Timezone whereExternalId($value)
 * @method static Builder|Timezone whereHasDst($value)
 * @method static Builder|Timezone whereId($value)
 * @method static Builder|Timezone whereInDst($value)
 * @method static Builder|Timezone withTrashed()
 * @method static Builder|Timezone withoutTrashed()
 * @mixin Eloquent
 */
class Timezone extends Model
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
    protected $table = 'timezones';

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
        'external_id', 'has_dst', 'in_dst'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'has_dst' => 'boolean',
        'in_dst'  => 'boolean'
    ];

    //--------------------------------------------------------------------------
    // Has many relations

    /**
     * @return HasMany
     */
    public function timeChanges() : HasMany
    {
        return $this->hasMany(TimezoneTimeChange::class);
    }

    //--------------------------------------------------------------------------
    // Belongs to many relations

    /**
     * @return BelongsToMany
     */
    public function offsets() : BelongsToMany
    {
        return $this->belongsToMany(
            TimezoneOffset::class,
            'offset_timezone',
            'timezone_id',
            'offset_id'
        )->withPivot([
            'is_dst'
        ]);
    }

    //--------------------------------------------------------------------------
    // Methods

    /**
     * @return TimezoneOffset
     */
    public function getCurrentOffset() : TimezoneOffset
    {
        return $this->offsets
            ->where('pivot.is_dst', '=', $this->in_dst)
            ->first();
    }

    /**
     * @return TimezoneOffset|null
     */
    public function getStandardOffset() : ?TimezoneOffset
    {
        return $this->offsets
            ->where('pivot.is_dst', '=', false)
            ->first();
    }

    /**
     * @return TimezoneOffset|null
     */
    public function getDstOffset() : ?TimezoneOffset
    {
        return $this->offsets
            ->where('pivot.is_dst', '=', true)
            ->first();
    }
}
