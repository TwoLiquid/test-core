<?php

namespace App\Models\MySql\Receipt\Transaction;

use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\Receipt\AddFundsReceipt;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Eloquent;
use Illuminate\Support\Carbon;

/**
 * App\Models\MySql\Receipt\Transaction\AddFundsTransaction
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
 * @property-read AddFundsReceipt $receipt
 * @method static Builder|AddFundsTransaction newModelQuery()
 * @method static Builder|AddFundsTransaction newQuery()
 * @method static Builder|AddFundsTransaction onlyTrashed()
 * @method static Builder|AddFundsTransaction query()
 * @method static Builder|AddFundsTransaction whereAmount($value)
 * @method static Builder|AddFundsTransaction whereCreatedAt($value)
 * @method static Builder|AddFundsTransaction whereDeletedAt($value)
 * @method static Builder|AddFundsTransaction whereDescription($value)
 * @method static Builder|AddFundsTransaction whereExternalId($value)
 * @method static Builder|AddFundsTransaction whereId($value)
 * @method static Builder|AddFundsTransaction whereMethodId($value)
 * @method static Builder|AddFundsTransaction whereReceiptId($value)
 * @method static Builder|AddFundsTransaction whereTransactionFee($value)
 * @method static Builder|AddFundsTransaction whereUpdatedAt($value)
 * @method static Builder|AddFundsTransaction withTrashed()
 * @method static Builder|AddFundsTransaction withoutTrashed()
 * @mixin Eloquent
 */
class AddFundsTransaction extends Model
{
    use SoftDeletes;

    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'add_funds_transactions';

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
        return $this->belongsTo(AddFundsReceipt::class);
    }

    /**
     * @return BelongsTo
     */
    public function method() : BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
