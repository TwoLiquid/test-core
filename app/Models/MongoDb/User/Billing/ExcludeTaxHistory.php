<?php

namespace App\Models\MongoDb\User\Billing;

use App\Models\MySql\Admin\Admin;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use MongoDB\Laravel\Eloquent\Model;

/**
 * App\Models\MongoDb\User\Billing\ExcludeTaxHistory
 *
 * @property string $_id
 * @property int $vat_number_proof_id
 * @property bool $value
 * @property int $admin_id
 * @property Carbon $created_at
 * @property-read Admin $admin
 * @property-read VatNumberProof $vatNumberProof
 * @method static Builder|ExcludeTaxHistory find(string $id)
 * @method static Builder|ExcludeTaxHistory query()
 */
class ExcludeTaxHistory extends Model
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
    protected $collection = 'exclude_tax_history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vat_number_proof_id', 'value', 'admin_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'value'      => 'boolean'
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
    public function vatNumberProof() : BelongsTo
    {
        return $this->belongsTo( VatNumberProof::class);
    }
}
