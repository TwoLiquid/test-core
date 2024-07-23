<?php

namespace App\Models\MySql\TaxRule;

use App\Models\MySql\Place\RegionPlace;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Eloquent;

/**
 * App\Models\MySql\TaxRule\TaxRuleRegion
 *
 * @property int $id
 * @property int $tax_rule_country_id
 * @property string $region_place_id
 * @property float $tax_rate
 * @property Carbon $from_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read RegionPlace $regionPlace
 * @property-read TaxRuleCountry $taxRuleCountry
 * @method static Builder|TaxRuleRegion newModelQuery()
 * @method static Builder|TaxRuleRegion newQuery()
 * @method static Builder|TaxRuleRegion onlyTrashed()
 * @method static Builder|TaxRuleRegion query()
 * @method static Builder|TaxRuleRegion whereCreatedAt($value)
 * @method static Builder|TaxRuleRegion whereDeletedAt($value)
 * @method static Builder|TaxRuleRegion whereFromDate($value)
 * @method static Builder|TaxRuleRegion whereId($value)
 * @method static Builder|TaxRuleRegion whereRegionPlaceId($value)
 * @method static Builder|TaxRuleRegion whereTaxRate($value)
 * @method static Builder|TaxRuleRegion whereTaxRuleCountryId($value)
 * @method static Builder|TaxRuleRegion whereUpdatedAt($value)
 * @method static Builder|TaxRuleRegion withTrashed()
 * @method static Builder|TaxRuleRegion withoutTrashed()
 * @mixin Eloquent
 */
class TaxRuleRegion extends Model
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
    protected $table = 'tax_rule_regions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tax_rule_country_id', 'region_place_id', 'tax_rate', 'from_date'
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
    public function taxRuleCountry() : BelongsTo
    {
        return $this->belongsTo(
            TaxRuleCountry::class,
            'tax_rule_country_id',
            'id'
        );
    }

    /**
     * @return BelongsTo
     */
    public function regionPlace() : BelongsTo
    {
        return $this->belongsTo(
            RegionPlace::class,
            'region_place_id',
            'place_id'
        );
    }
}
