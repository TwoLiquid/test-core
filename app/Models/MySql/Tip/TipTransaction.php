<?php

namespace App\Models\MySql\Tip;

use App\Models\MySql\Payment\PaymentMethod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Eloquent;

/**
 * App\Models\MySql\Tip\TipTransaction
 *
 * @property int $id
 * @property int $tip_id
 * @property int $method_id
 * @property string|null $external_id
 * @property float $amount
 * @property float|null $transaction_fee
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read PaymentMethod $method
 * @property-read Tip $tip
 * @method static Builder|TipTransaction newModelQuery()
 * @method static Builder|TipTransaction newQuery()
 * @method static Builder|TipTransaction onlyTrashed()
 * @method static Builder|TipTransaction query()
 * @method static Builder|TipTransaction whereAmount($value)
 * @method static Builder|TipTransaction whereCreatedAt($value)
 * @method static Builder|TipTransaction whereDeletedAt($value)
 * @method static Builder|TipTransaction whereDescription($value)
 * @method static Builder|TipTransaction whereExternalId($value)
 * @method static Builder|TipTransaction whereId($value)
 * @method static Builder|TipTransaction whereMethodId($value)
 * @method static Builder|TipTransaction whereTipId($value)
 * @method static Builder|TipTransaction whereTransactionFee($value)
 * @method static Builder|TipTransaction whereUpdatedAt($value)
 * @method static Builder|TipTransaction withTrashed()
 * @method static Builder|TipTransaction withoutTrashed()
 * @mixin Eloquent
 */
class TipTransaction extends Model
{
    use SoftDeletes;

    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'tip_transactions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tip_id', 'method_id', 'external_id', 'amount', 'transaction_fee', 'description'
    ];

    //--------------------------------------------------------------------------
    // Belongs to relations

    /**
     * @return BelongsTo
     */
    public function tip() : BelongsTo
    {
        return $this->belongsTo(Tip::class);
    }

    /**
     * @return BelongsTo
     */
    public function method() : BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
