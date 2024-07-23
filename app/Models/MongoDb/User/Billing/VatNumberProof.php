<?php

namespace App\Models\MongoDb\User\Billing;

use App\Lists\VatNumberProof\Status\VatNumberProofStatusList;
use App\Lists\VatNumberProof\Status\VatNumberProofStatusListItem;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\Billing;
use App\Models\MySql\Place\CountryPlace;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\HasMany;

/**
 * App\Models\MongoDb\User\Billing\VatNumberProof
 *
 * @property string $_id
 * @property int $billing_id
 * @property string $country_place_id
 * @property string $company_name
 * @property string $vat_number
 * @property bool $exclude_tax
 * @property int $status_id
 * @property int $admin_id
 * @property string $action_date
 * @property string $exclude_tax_date
 * @property string $status_change_date
 * @property Carbon $created_at
 * @property-read Billing $billing
 * @property-read CountryPlace $countryPlace
 * @property-read Admin $admin
 * @property-read ExcludeTaxHistory[] $excludeTaxHistory
 * @method static Builder|VatNumberProof find(string $id)
 * @method static Builder|VatNumberProof query()
 */
class VatNumberProof extends Model
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
    protected $collection = 'vat_number_proofs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'billing_id', 'country_place_id', 'company_name', 'vat_number', 'exclude_tax',
        'status_id', 'admin_id', 'action_date', 'exclude_tax_date', 'status_change_date'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'exclude_tax'        => 'boolean',
        'action_date'        => 'datetime',
        'exclude_tax_date'   => 'datetime',
        'status_change_date' => 'datetime',
        'created_at'         => 'datetime'
    ];

    //--------------------------------------------------------------------------
    // Belongs to relations

    /**
     * @return BelongsTo
     */
    public function admin() : BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * @return BelongsTo
     */
    public function billing() : BelongsTo
    {
        return $this->belongsTo( Billing::class);
    }

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

    //--------------------------------------------------------------------------
    // Has many relations

    /**
     * @return HasMany
     */
    public function excludeTaxHistory() : HasMany
    {
        return $this->hasMany(ExcludeTaxHistory::class);
    }

    //--------------------------------------------------------------------------
    // Lists accessors

    /**
     * @return VatNumberProofStatusListItem|null
     */
    public function getStatus() : ?VatNumberProofStatusListItem
    {
        return VatNumberProofStatusList::getItem(
            $this->status_id
        );
    }
}
