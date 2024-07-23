<?php

namespace App\Models\MySql;

use App\Models\MySql\Activity\Activity;
use App\Models\MySql\Activity\ActivityTag;
use App\Models\MySql\Vybe\Vybe;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\Translatable\HasTranslations;
use Eloquent;

/**
 * App\Models\MySql\Category
 *
 * @property int $id
 * @property int|null $parent_id
 * @property string $code
 * @property array $name
 * @property bool $visible
 * @property int $position
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 * @property-read Collection<int, ActivityTag> $categoryTags
 * @property-read int|null $category_tags_count
 * @property-read Category|null $parent
 * @property-read Collection<int, Category> $subcategories
 * @property-read int|null $subcategories_count
 * @property-read Collection<int, ActivityTag> $subcategoryTags
 * @property-read int|null $subcategory_tags_count
 * @property-read Collection<int, Vybe> $vybes
 * @property-read int|null $vybes_count
 * @method static Builder|Category newModelQuery()
 * @method static Builder|Category newQuery()
 * @method static Builder|Category onlyTrashed()
 * @method static Builder|Category query()
 * @method static Builder|Category whereCode($value)
 * @method static Builder|Category whereDeletedAt($value)
 * @method static Builder|Category whereId($value)
 * @method static Builder|Category whereLocale(string $column, string $locale)
 * @method static Builder|Category whereLocales(string $column, array $locales)
 * @method static Builder|Category whereName($value)
 * @method static Builder|Category whereParentId($value)
 * @method static Builder|Category wherePosition($value)
 * @method static Builder|Category whereVisible($value)
 * @method static Builder|Category withTrashed()
 * @method static Builder|Category withoutTrashed()
 * @mixin Eloquent
 */
class Category extends Model
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
    protected $table = 'categories';

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
        'parent_id', 'code', 'name', 'visible', 'position'
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
    public function parent() : BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    //--------------------------------------------------------------------------
    // Has many relations

    /**
     * @return HasMany
     */
    public function activities() : HasMany
    {
        return $this->hasMany(
            Activity::class,
            'category_id',
            'id'
        );
    }

    /**
     * @return HasMany
     */
    public function subcategories() : HasMany
    {
        return $this->hasMany(
            Category::class,
            'parent_id',
            'id'
        );
    }

    /**
     * @return HasMany
     */
    public function categoryTags() : HasMany
    {
        return $this->hasMany(
            ActivityTag::class,
            'category_id',
            'id'
        );
    }

    /**
     * @return HasMany
     */
    public function subcategoryTags() : HasMany
    {
        return $this->hasMany(
            ActivityTag::class,
            'subcategory_id',
            'id'
        );
    }

    /**
     * @return HasManyThrough
     */
    public function vybes() : HasManyThrough
    {
        return $this->hasManyThrough(
            Vybe::class,
            Activity::class,
            'category_id',
            'activity_id',
            'id',
            'id'
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
