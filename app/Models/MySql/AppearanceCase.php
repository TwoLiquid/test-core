<?php

namespace App\Models\MySql;

use App\Lists\Vybe\Appearance\VybeAppearanceList;
use App\Lists\Vybe\Appearance\VybeAppearanceListItem;
use App\Models\MongoDb\Vybe\VybeAppearanceCaseSupport;
use App\Models\MySql\Order\OrderItem;
use App\Models\MySql\Place\CityPlace;
use App\Models\MySql\Vybe\Vybe;
use Closure;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use MongoDB\Laravel\Eloquent\HybridRelations;
use MongoDB\Laravel\Relations\HasOne;
use Eloquent;

/**
 * App\Models\MySql\AppearanceCase
 *
 * @property int $id
 * @property int $vybe_id
 * @property int $appearance_id
 * @property int|null $unit_id
 * @property string|null $city_place_id
 * @property float|null $price
 * @property string|null $description
 * @property bool|null $same_location
 * @property bool|null $enabled
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read CityPlace|null $cityPlace
 * @property-read string|null $unit_suggestion
 * @property-read Collection<int, OrderItem> $orderItems
 * @property-read int|null $order_items_count
 * @property-read Collection<int, Platform> $platforms
 * @property-read int|null $platforms_count
 * @property-read VybeAppearanceCaseSupport|null $support
 * @property-read Unit|null $unit
 * @property-read Vybe $vybe
 * @method static Builder|AppearanceCase addHybridHas(Relation $relation, string $operator = '>=', string $count = 1, string $boolean = 'and', ?Closure $callback = null)
 * @method static Builder|AppearanceCase newModelQuery()
 * @method static Builder|AppearanceCase newQuery()
 * @method static Builder|AppearanceCase onlyTrashed()
 * @method static Builder|AppearanceCase query()
 * @method static Builder|AppearanceCase whereAppearanceId($value)
 * @method static Builder|AppearanceCase whereCityPlaceId($value)
 * @method static Builder|AppearanceCase whereCreatedAt($value)
 * @method static Builder|AppearanceCase whereDeletedAt($value)
 * @method static Builder|AppearanceCase whereDescription($value)
 * @method static Builder|AppearanceCase whereEnabled($value)
 * @method static Builder|AppearanceCase whereId($value)
 * @method static Builder|AppearanceCase wherePrice($value)
 * @method static Builder|AppearanceCase whereSameLocation($value)
 * @method static Builder|AppearanceCase whereUnitId($value)
 * @method static Builder|AppearanceCase whereUpdatedAt($value)
 * @method static Builder|AppearanceCase whereVybeId($value)
 * @method static Builder|AppearanceCase withTrashed()
 * @method static Builder|AppearanceCase withoutTrashed()
 * @mixin Eloquent
 */
class AppearanceCase extends Model
{
    use SoftDeletes, HybridRelations;

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
    protected $table = 'appearance_cases';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vybe_id', 'appearance_id', 'unit_id', 'city_place_id', 'price',
        'description', 'same_location', 'enabled'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'same_location' => 'boolean',
        'enabled'       => 'boolean'
    ];

    //--------------------------------------------------------------------------
    // Belongs to relations

    /**
     * @return BelongsTo
     */
    public function vybe() : BelongsTo
    {
        return $this->belongsTo(Vybe::class);
    }

    /**
     * @return BelongsTo
     */
    public function unit() : BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * @return BelongsTo
     */
    public function cityPlace() : BelongsTo
    {
        return $this->belongsTo(
            CityPlace::class,
            'city_place_id',
            'place_id'
        );
    }

    //--------------------------------------------------------------------------
    // Belongs to many relations

    /**
     * @return BelongsToMany
     */
    public function platforms() : BelongsToMany
    {
        return $this->belongsToMany(
            Platform::class,
            'appearance_case_platform',
            'appearance_case_id',
            'platform_id'
        )->using(
            AppearanceCasePlatform::class
        );
    }

    //--------------------------------------------------------------------------
    // Has one relations

    /**
     * @return HasOne
     */
    public function support() : HasOne
    {
        return $this->hasOne(VybeAppearanceCaseSupport::class);
    }

    //--------------------------------------------------------------------------
    // Has many relations

    /**
     * @return HasMany
     */
    public function orderItems() : HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    //--------------------------------------------------------------------------
    // Magic accessors

    /**
     * @return string|null
     */
    public function getUnitSuggestionAttribute() : ?string
    {
        return $this->support ?
            $this->support->unit_suggestion :
            null;
    }

    //--------------------------------------------------------------------------
    // Lists accessors

    /**
     * @return VybeAppearanceListItem|null
     */
    public function getAppearance() : ?VybeAppearanceListItem
    {
        return VybeAppearanceList::getItem(
            $this->appearance_id
        );
    }
}
