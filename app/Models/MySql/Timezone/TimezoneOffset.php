<?php

namespace App\Models\MySql\Timezone;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\Translatable\HasTranslations;
use Eloquent;

/**
 * App\Models\MySql\Timezone\TimezoneOffset
 *
 * @property int $id
 * @property string $code
 * @property array $name
 * @property int $offset
 * @property Carbon|null $deleted_at
 * @property-read Timezone $timeslot
 * @property-read Collection<int, Timezone> $timezones
 * @property-read int|null $timezones_count
 * @method static Builder|TimezoneOffset newModelQuery()
 * @method static Builder|TimezoneOffset newQuery()
 * @method static Builder|TimezoneOffset onlyTrashed()
 * @method static Builder|TimezoneOffset query()
 * @method static Builder|TimezoneOffset whereCode($value)
 * @method static Builder|TimezoneOffset whereDeletedAt($value)
 * @method static Builder|TimezoneOffset whereId($value)
 * @method static Builder|TimezoneOffset whereIsDst($value)
 * @method static Builder|TimezoneOffset whereLocale(string $column, string $locale)
 * @method static Builder|TimezoneOffset whereLocales(string $column, array $locales)
 * @method static Builder|TimezoneOffset whereName($value)
 * @method static Builder|TimezoneOffset whereOffset($value)
 * @method static Builder|TimezoneOffset withTrashed()
 * @method static Builder|TimezoneOffset withoutTrashed()
 * @mixin Eloquent
 */
class TimezoneOffset extends Model
{
    use HasTranslations, SoftDeletes;

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
    protected $table = 'timezone_offsets';

    /**
     * Provide default timestamps usage
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Translation field
     *
     * @var array
     */
    public array $translatable = [
        'name'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'name', 'offset'
    ];

    //--------------------------------------------------------------------------
    // Belongs to relations

    /**
     * @return BelongsTo
     */
    public function timeslot() : BelongsTo
    {
        return $this->belongsTo(Timezone::class);
    }

    //--------------------------------------------------------------------------
    // Belongs to many relations

    /**
     * @return BelongsToMany
     */
    public function timezones() : BelongsToMany
    {
        return $this->belongsToMany(
            Timezone::class,
            'offset_timezone',
            'offset_id',
            'timezone_id'
        )->withPivot([
            'is_dst'
        ]);
    }
}
