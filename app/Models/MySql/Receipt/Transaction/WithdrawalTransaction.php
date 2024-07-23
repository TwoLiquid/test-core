<?php

namespace App\Models\MySql\Receipt\Transaction;

use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\Receipt\WithdrawalReceipt;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Eloquent;

/**
 * App\Models\MySql\Receipt\Transaction\WithdrawalTransaction
 *
 * @property int $id
 * @property int $receipt_id
 * @property int $method_id
 * @property string|null $external_id
 * @property float $amount
 * @property float|null $transaction_fee
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read PaymentMethod $method
 * @property-read WithdrawalReceipt $receipt
 * @method static Builder|WithdrawalTransaction newModelQuery()
 * @method static Builder|WithdrawalTransaction newQuery()
 * @method static Builder|WithdrawalTransaction onlyTrashed()
 * @method static Builder|WithdrawalTransaction query()
 * @method static Builder|WithdrawalTransaction whereAmount($value)
 * @method static Builder|WithdrawalTransaction whereCreatedAt($value)
 * @method static Builder|WithdrawalTransaction whereDeletedAt($value)
 * @method static Builder|WithdrawalTransaction whereDescription($value)
 * @method static Builder|WithdrawalTransaction whereExternalId($value)
 * @method static Builder|WithdrawalTransaction whereId($value)
 * @method static Builder|WithdrawalTransaction whereMethodId($value)
 * @method static Builder|WithdrawalTransaction whereReceiptId($value)
 * @method static Builder|WithdrawalTransaction whereTransactionFee($value)
 * @method static Builder|WithdrawalTransaction whereUpdatedAt($value)
 * @method static Builder|WithdrawalTransaction withTrashed()
 * @method static Builder|WithdrawalTransaction withoutTrashed()
 * @mixin Eloquent
 */
class WithdrawalTransaction extends Model
{
    use SoftDeletes;

    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'withdrawal_transactions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'receipt_id', 'method_id', 'external_id', 'amount',
        'transaction_fee', 'description', 'created_at'
    ];

    //--------------------------------------------------------------------------
    // Belongs to relations

    /**
     * @return BelongsTo
     */
    public function receipt() : BelongsTo
    {
        return $this->belongsTo(WithdrawalReceipt::class);
    }

    /**
     * @return BelongsTo
     */
    public function method() : BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
