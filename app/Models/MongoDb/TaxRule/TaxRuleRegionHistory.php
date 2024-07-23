<?php

namespace App\Models\MongoDb\TaxRule;

use App\Models\MySql\Admin\Admin;
use App\Models\MySql\TaxRule\TaxRuleRegion;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MongoDB\Laravel\Eloquent\Model;

/**
 * App\Models\MongoDb\TaxRule\TaxRuleRegionHistory
 *
 * @property string $_id
 * @property int $tax_rule_region_id
 * @property double $from_tax_rate
 * @property Carbon $from_date
 * @property double $to_tax_rate
 * @property Carbon $to_date
 * @property-read TaxRuleRegion $taxRuleRegion
 * @property-read Admin $admin
 * @method static Builder|TaxRuleRegionHistory find(string $id)
 * @method static Builder|TaxRuleRegionHistory query()
 */
class TaxRuleRegionHistory extends Model
{
    /**
     * Database connection type
     *
     * @var string
     */
    protected $connection = 'mongodb';

    /**
     * Database collection name
     *
     * @var string
     */
    protected $collection = 'tax_rule_region_history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tax_rule_region_id', 'from_tax_rate', 'from_date',
        'to_tax_rate', 'to_date', 'admin_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'from_date' => 'datetime',
        'to_date'   => 'datetime'
    ];

    //--------------------------------------------------------------------------
    // Belongs to relations

    /**
     * @return BelongsTo
     */
    public function taxRuleRegion() : BelongsTo
    {
        return $this->belongsTo(TaxRuleRegion::class);
    }

    /**
     * @return BelongsTo
     */
    public function admin() : BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }
}
