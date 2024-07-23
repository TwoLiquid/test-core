<?php

namespace App\Models\MySql\Place;

use App\Models\MySql\PhoneCode;
use App\Models\MySql\TaxRule\TaxRuleCountry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Translatable\HasTranslations;
use Eloquent;

/**
 * App\Models\MySql\Place\CountryPlace
 *
 * @property int $id
 * @property string $place_id
 * @property string $code
 * @property array $name
 * @property array $official_name
 * @property float|null $latitude
 * @property float|null $longitude
 * @property bool $has_regions
 * @property bool $excluded
 * @property-read PhoneCode|null $defaultPhoneCode
 * @property-read Collection<int, PhoneCode> $phoneCodes
 * @property-read int|null $phone_codes_count
 * @property-read TaxRuleCountry|null $taxRuleCountry
 * @method static Builder|CountryPlace newModelQuery()
 * @method static Builder|CountryPlace newQuery()
 * @method static Builder|CountryPlace query()
 * @method static Builder|CountryPlace whereCode($value)
 * @method static Builder|CountryPlace whereExcluded($value)
 * @method static Builder|CountryPlace whereHasRegions($value)
 * @method static Builder|CountryPlace whereId($value)
 * @method static Builder|CountryPlace whereLatitude($value)
 * @method static Builder|CountryPlace whereLocale(string $column, string $locale)
 * @method static Builder|CountryPlace whereLocales(string $column, array $locales)
 * @method static Builder|CountryPlace whereLongitude($value)
 * @method static Builder|CountryPlace whereName($value)
 * @method static Builder|CountryPlace whereOfficialName($value)
 * @method static Builder|CountryPlace wherePlaceId($value)
 * @mixin Eloquent
 */
class CountryPlace extends Model
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
    protected $table = 'country_places';

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
        'name',
        'official_name'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'place_id', 'code', 'name', 'official_name', 'latitude', 'longitude',
        'has_regions', 'excluded'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'has_regions' => 'boolean',
        'excluded'    => 'boolean'
    ];

    //--------------------------------------------------------------------------
    // Has one relations

    /**
     * @return HasOne
     */
    public function defaultPhoneCode() : HasOne
    {
        return $this->hasOne(
            PhoneCode::class,
            'country_place_id',
            'place_id'
        )->where(
            'is_default',
            '=',
            true
        );
    }

    /**
     * @return HasOne
     */
    public function taxRuleCountry() : HasOne
    {
        return $this->hasOne(
            TaxRuleCountry::class,
            'country_place_id',
            'place_id'
        );
    }

    //--------------------------------------------------------------------------
    // Has many relations

    /**
     * @return HasMany
     */
    public function phoneCodes() : HasMany
    {
        return $this->hasMany(
            PhoneCode::class,
            'country_place_id',
            'place_id'
        );
    }
}
