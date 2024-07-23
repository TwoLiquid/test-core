<?php

namespace App\Models\MySql;

use App\Lists\Unit\Type\UnitTypeList;
use App\Lists\Unit\Type\UnitTypeListItem;
use App\Models\MySql\Activity\Activity;
use App\Models\MySql\Vybe\Vybe;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\Translatable\HasTranslations;
use Eloquent;

/**
 * App\Models\MySql\Unit
 *
 * @property int $id
 * @property int $type_id
 * @property string $code
 * @property array $name
 * @property int|null $duration
 * @property bool $visible
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 * @property-read Collection<int, Vybe> $vybes
 * @property-read int|null $vybes_count
 * @method static Builder|Unit newModelQuery()
 * @method static Builder|Unit newQuery()
 * @method static Builder|Unit onlyTrashed()
 * @method static Builder|Unit query()
 * @method static Builder|Unit whereCode($value)
 * @method static Builder|Unit whereDeletedAt($value)
 * @method static Builder|Unit whereDuration($value)
 * @method static Builder|Unit whereId($value)
 * @method static Builder|Unit whereLocale(string $column, string $locale)
 * @method static Builder|Unit whereLocales(string $column, array $locales)
 * @method static Builder|Unit whereName($value)
 * @method static Builder|Unit whereTypeId($value)
 * @method static Builder|Unit whereVisible($value)
 * @method static Builder|Unit withTrashed()
 * @method static Builder|Unit withoutTrashed()
 * @mixin Eloquent
 */
class Unit extends Model
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
    protected $table = 'units';

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
    public array $translatable = ['name'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type_id', 'name', 'code', 'duration', 'visible'
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
    public function activities() : BelongsToMany
    {
        return $this->belongsToMany(
            Activity::class,
            'activity_unit',
            'unit_id',
            'activity_id'
        )->withPivot([
            'visible',
            'position'
        ]);
    }

    //--------------------------------------------------------------------------
    // Has many through relations

    /**
     * @return HasManyThrough
     */
    public function vybes() : HasManyThrough
    {
        return $this->hasManyThrough(
            Vybe::class,
            AppearanceCase::class,
            'unit_id',
            'id',
            'id',
            'vybe_id'
        );
    }

    //--------------------------------------------------------------------------
    // Lists accessors

    /**
     * @return UnitTypeListItem|null
     */
    public function getType() : ?UnitTypeListItem
    {
        return UnitTypeList::getItem(
            $this->type_id
        );
    }
}
