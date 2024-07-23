<?php

namespace App\Models\MySql\Timezone;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Eloquent;

/**
 * App\Models\MySql\Timezone\TimezoneTimeChange
 *
 * @property int $id
 * @property int $timezone_id
 * @property bool $to_dst
 * @property Carbon $started_at
 * @property Carbon $completed_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Timezone $timezone
 * @method static Builder|TimezoneTimeChange newModelQuery()
 * @method static Builder|TimezoneTimeChange newQuery()
 * @method static Builder|TimezoneTimeChange query()
 * @method static Builder|TimezoneTimeChange whereCompletedAt($value)
 * @method static Builder|TimezoneTimeChange whereCreatedAt($value)
 * @method static Builder|TimezoneTimeChange whereId($value)
 * @method static Builder|TimezoneTimeChange whereStartedAt($value)
 * @method static Builder|TimezoneTimeChange whereTimezoneId($value)
 * @method static Builder|TimezoneTimeChange whereToDst($value)
 * @method static Builder|TimezoneTimeChange whereUpdatedAt($value)
 * @mixin Eloquent
 */
class TimezoneTimeChange extends Model
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
    protected $table = 'timezone_time_changes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'timezone_id', 'to_dst', 'started_at', 'completed_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'to_dst'       => 'boolean',
        'started_at'   => 'datetime',
        'completed_at' => 'datetime'
    ];

    //--------------------------------------------------------------------------
    // Belongs to relations

    /**
     * @return BelongsTo
     */
    public function timezone() : BelongsTo
    {
        return $this->belongsTo(Timezone::class);
    }
}
