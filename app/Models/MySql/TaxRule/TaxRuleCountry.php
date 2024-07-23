<?php

namespace App\Models\MySql\TaxRule;

use App\Models\MySql\Place\CountryPlace;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Eloquent;

/**
 * App\Models\MySql\TaxRule\TaxRuleCountry
 *
 * @property int $id
 * @property string $country_place_id
 * @property float $tax_rate
 * @property Carbon $from_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read CountryPlace $countryPlace
 * @property-read Collection<int, TaxRuleRegion> $taxRuleRegions
 * @property-read int|null $tax_rule_regions_count
 * @method static Builder|TaxRuleCountry newModelQuery()
 * @method static Builder|TaxRuleCountry newQuery()
 * @method static Builder|TaxRuleCountry onlyTrashed()
 * @method static Builder|TaxRuleCountry query()
 * @method static Builder|TaxRuleCountry whereCountryPlaceId($value)
 * @method static Builder|TaxRuleCountry whereCreatedAt($value)
 * @method static Builder|TaxRuleCountry whereDeletedAt($value)
 * @method static Builder|TaxRuleCountry whereFromDate($value)
 * @method static Builder|TaxRuleCountry whereId($value)
 * @method static Builder|TaxRuleCountry whereTaxRate($value)
 * @method static Builder|TaxRuleCountry whereUpdatedAt($value)
 * @method static Builder|TaxRuleCountry withTrashed()
 * @method static Builder|TaxRuleCountry withoutTrashed()
 * @mixin Eloquent
 */
class TaxRuleCountry extends Model
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
    protected $table = 'tax_rule_countries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'country_place_id', 'tax_rate', 'from_date'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'from_date' => 'datetime'
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
            'country_place_id',
            'place_id'
        );
    }

    /**
     * @return HasMany
     */
    public function taxRuleRegions() : HasMany
    {
        return $this->hasMany(
            TaxRuleRegion::class,
            'tax_rule_country_id',
            'id'
        );
    }
}
