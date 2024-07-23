<?php

namespace App\Models\MySql\Place;

use App\Models\MySql\TaxRule\TaxRuleRegion;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Translatable\HasTranslations;
use Eloquent;

/**
 * App\Models\MySql\Place\RegionPlace
 *
 * @property int $id
 * @property string $country_place_id
 * @property string $place_id
 * @property array $name
 * @property string|null $code
 * @property-read CountryPlace $countryPlace
 * @property-read TaxRuleRegion|null $taxRuleRegion
 * @method static Builder|RegionPlace newModelQuery()
 * @method static Builder|RegionPlace newQuery()
 * @method static Builder|RegionPlace query()
 * @method static Builder|RegionPlace whereCode($value)
 * @method static Builder|RegionPlace whereCountryPlaceId($value)
 * @method static Builder|RegionPlace whereId($value)
 * @method static Builder|RegionPlace whereLocale(string $column, string $locale)
 * @method static Builder|RegionPlace whereLocales(string $column, array $locales)
 * @method static Builder|RegionPlace whereName($value)
 * @method static Builder|RegionPlace wherePlaceId($value)
 * @mixin Eloquent
 */
class RegionPlace extends Model
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
    protected $table = 'region_places';

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
        'country_place_id', 'place_id', 'name', 'code'
    ];

    //--------------------------------------------------------------------------
    // Belongs to relations

    /**
     * @return BelongsTo
     */
    public function countryPlace() : BelongsTo
    {
        return $this->belongsTo(
            CountryPlace::class,
            'place_id'
        );
    }

    //--------------------------------------------------------------------------
    // Has one relations

    /**
     * @return HasOne
     */
    public function taxRuleRegion() : HasOne
    {
        return $this->hasOne(
            TaxRuleRegion::class,
            'region_place_id',
            'place_id'
        );
    }
}
