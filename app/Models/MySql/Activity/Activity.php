<?php

namespace App\Models\MySql\Activity;

use App\Models\MySql\Category;
use App\Models\MySql\Device;
use App\Models\MySql\Platform;
use App\Models\MySql\Unit;
use App\Models\MySql\Vybe\Vybe;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\Translatable\HasTranslations;
use Eloquent;

/**
 * App\Models\MySql\Activity\Activity
 *
 * @property int $id
 * @property int $category_id
 * @property string $code
 * @property array $name
 * @property bool $visible
 * @property int $position
 * @property Carbon|null $deleted_at
 * @property-read Category $category
 * @property-read Collection<int, Device> $devices
 * @property-read int|null $devices_count
 * @property-read Collection<int, Platform> $platforms
 * @property-read int|null $platforms_count
 * @property-read Collection<int, ActivityTag> $tags
 * @property-read int|null $tags_count
 * @property-read Collection<int, Unit> $units
 * @property-read int|null $units_count
 * @property-read Collection<int, Vybe> $vybes
 * @property-read int|null $vybes_count
 * @method static Builder|Activity newModelQuery()
 * @method static Builder|Activity newQuery()
 * @method static Builder|Activity onlyTrashed()
 * @method static Builder|Activity query()
 * @method static Builder|Activity whereCategoryId($value)
 * @method static Builder|Activity whereCode($value)
 * @method static Builder|Activity whereDeletedAt($value)
 * @method static Builder|Activity whereId($value)
 * @method static Builder|Activity whereLocale(string $column, string $locale)
 * @method static Builder|Activity whereLocales(string $column, array $locales)
 * @method static Builder|Activity whereName($value)
 * @method static Builder|Activity wherePosition($value)
 * @method static Builder|Activity whereVisible($value)
 * @method static Builder|Activity withTrashed()
 * @method static Builder|Activity withoutTrashed()
 * @mixin Eloquent
 */
class Activity extends Model
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
    protected $table = 'activities';

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
        'category_id', 'name', 'code', 'visible', 'position'
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
    // Belongs to relations

    /**
     * @return BelongsTo
     */
    public function category() : BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    //--------------------------------------------------------------------------
    // Has many relations

    /**
     * @return HasMany
     */
    public function vybes() : HasMany
    {
        return $this->hasMany(
            Vybe::class,
            'activity_id',
            'id'
        );
    }

    //--------------------------------------------------------------------------
    // Belongs to many relations

    /**
     * @return BelongsToMany
     */
    public function devices() : BelongsToMany
    {
        return $this->belongsToMany(
            Device::class
        );
    }

    /**
     * @return BelongsToMany
     */
    public function platforms() : BelongsToMany
    {
        return $this->belongsToMany(
            Platform::class
        );
    }

    /**
     * @return BelongsToMany
     */
    public function units() : BelongsToMany
    {
        return $this->belongsToMany(
            Unit::class,
            'activity_unit',
            'activity_id',
            'unit_id'
        )->withPivot([
            'visible',
            'position'
        ]);
    }

    /**
     * @return BelongsToMany
     */
    public function tags() : BelongsToMany
    {
        return $this->belongsToMany(
            ActivityTag::class,
            'activity_tag',
            'activity_id',
            'tag_id'
        );
    }

    //--------------------------------------------------------------------------
    // Methods

    /**
     * @return int
     */
    public static function getLastPosition() : int
    {
        return (new static)::query()
                ->orderBy('position', 'desc')
                ->first()
                ->position + 1;
    }
}
