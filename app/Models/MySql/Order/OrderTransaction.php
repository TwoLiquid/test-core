<?php

namespace App\Models\MySql\Order;

use App\Models\MySql\Payment\PaymentMethod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Eloquent;

/**
 * App\Models\MySql\Order\OrderTransaction
 *
 * @property int $id
 * @property int $invoice_id
 * @property int $method_id
 * @property string|null $external_id
 * @property float $amount
 * @property float|null $transaction_fee
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read OrderInvoice $invoice
 * @property-read PaymentMethod $method
 * @method static Builder|OrderTransaction newModelQuery()
 * @method static Builder|OrderTransaction newQuery()
 * @method static Builder|OrderTransaction onlyTrashed()
 * @method static Builder|OrderTransaction query()
 * @method static Builder|OrderTransaction whereAmount($value)
 * @method static Builder|OrderTransaction whereCreatedAt($value)
 * @method static Builder|OrderTransaction whereDeletedAt($value)
 * @method static Builder|OrderTransaction whereDescription($value)
 * @method static Builder|OrderTransaction whereExternalId($value)
 * @method static Builder|OrderTransaction whereId($value)
 * @method static Builder|OrderTransaction whereInvoiceId($value)
 * @method static Builder|OrderTransaction whereMethodId($value)
 * @method static Builder|OrderTransaction whereTransactionFee($value)
 * @method static Builder|OrderTransaction whereUpdatedAt($value)
 * @method static Builder|OrderTransaction withTrashed()
 * @method static Builder|OrderTransaction withoutTrashed()
 * @mixin Eloquent
 */
class OrderTransaction extends Model
{
    use SoftDeletes;

    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'order_transactions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'invoice_id', 'method_id', 'external_id', 'amount', 'transaction_fee', 'description'
    ];

    //--------------------------------------------------------------------------
    // Belongs to relations

    /**
     * @return BelongsTo
     */
    public function invoice() : BelongsTo
    {
        return $this->belongsTo(OrderInvoice::class);
    }

    /**
     * @return BelongsTo
     */
    public function method() : BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
