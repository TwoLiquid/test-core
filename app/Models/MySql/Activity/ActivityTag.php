<?php

namespace App\Models\MySql\Activity;

use App\Models\MySql\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;
use Eloquent;

/**
 * App\Models\MySql\Activity\ActivityTag
 *
 * @property int $id
 * @property int|null $category_id
 * @property int|null $subcategory_id
 * @property string $code
 * @property array $name
 * @property bool $visible_in_category
 * @property bool $visible_in_subcategory
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 * @property-read Category|null $category
 * @property-read Category|null $subcategory
 * @method static Builder|ActivityTag newModelQuery()
 * @method static Builder|ActivityTag newQuery()
 * @method static Builder|ActivityTag query()
 * @method static Builder|ActivityTag whereCategoryId($value)
 * @method static Builder|ActivityTag whereCode($value)
 * @method static Builder|ActivityTag whereId($value)
 * @method static Builder|ActivityTag whereLocale(string $column, string $locale)
 * @method static Builder|ActivityTag whereLocales(string $column, array $locales)
 * @method static Builder|ActivityTag whereName($value)
 * @method static Builder|ActivityTag whereSubcategoryId($value)
 * @method static Builder|ActivityTag whereVisibleInCategory($value)
 * @method static Builder|ActivityTag whereVisibleInSubcategory($value)
 * @mixin Eloquent
 */
class ActivityTag extends Model
{
    use HasTranslations;

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
    protected $table = 'activity_tags';

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
        'category_id', 'subcategory_id', 'name', 'code',
        'visible_in_category', 'visible_in_subcategory'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'visible_in_category'    => 'boolean',
        'visible_in_subcategory' => 'boolean'
    ];

    //--------------------------------------------------------------------------
    // Belong to relations

    /**
     * @return BelongsTo
     */
    public function category() : BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return BelongsTo
     */
    public function subcategory() : BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    //--------------------------------------------------------------------------
    // Belong to many relations

    /**
     * @return BelongsToMany
     */
    public function activities() : BelongsToMany
    {
        return $this->belongsToMany(
            Activity::class,
            'activity_tag',
            'tag_id',
            'activity_id'
        );
    }
}
