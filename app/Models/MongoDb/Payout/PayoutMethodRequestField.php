<?php

namespace App\Models\MongoDb\Payout;

use App\Models\MySql\Payment\PaymentMethodField;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MongoDB\Laravel\Relations\BelongsTo as MongoBelongsTo;
use MongoDB\Laravel\Eloquent\Model;

/**
 * App\Models\MongoDb\Payout\PayoutMethodRequestField
 *
 * @property string $_id
 * @property int $request_id
 * @property int $field_id
 * @property array $value
 * @property-read PayoutMethodRequest $request
 * @property-read PaymentMethodField $field
 * @method static Builder|PayoutMethodRequestField find(string $id)
 * @method static Builder|PayoutMethodRequestField query()
 */
class PayoutMethodRequestField extends Model
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
    protected $collection = 'payout_method_request_fields';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'request_id', 'field_id', 'value'
    ];

    //--------------------------------------------------------------------------
    // Belongs to relations

    /**
     * @return MongoBelongsTo
     */
    public function request() : MongoBelongsTo
    {
        return $this->belongsTo(PayoutMethodRequest::class);
    }

    /**
     * @return BelongsTo
     */
    public function field() : BelongsTo
    {
        return $this->belongsTo(PaymentMethodField::class);
    }
}
