<?php

namespace App\Models\MongoDb\TaxRule;

use App\Models\MySql\Admin\Admin;
use App\Models\MySql\TaxRule\TaxRuleCountry;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MongoDB\Laravel\Eloquent\Model;

/**
 * App\Models\MongoDb\TaxRule\TaxRuleCountryHistory
 *
 * @property string $_id
 * @property int $tax_rule_country_id
 * @property double $from_tax_rate
 * @property Carbon $from_date
 * @property double $to_tax_rate
 * @property Carbon $to_date
 * @property-read TaxRuleCountry $taxRuleCountry
 * @property-read Admin $admin
 * @method static Builder|TaxRuleCountryHistory find(string $id)
 * @method static Builder|TaxRuleCountryHistory query()
 */
class TaxRuleCountryHistory extends Model
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
    protected $collection = 'tax_rule_country_history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tax_rule_country_id', 'from_tax_rate', 'from_date',
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
    public function taxRuleCountry() : BelongsTo
    {
        return $this->belongsTo(TaxRuleCountry::class);
    }

    /**
     * @return BelongsTo
     */
    public function admin() : BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }
}
