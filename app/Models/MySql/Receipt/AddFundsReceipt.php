<?php

namespace App\Models\MySql\Receipt;

use App\Lists\AddFunds\Receipt\Status\AddFundsReceiptStatusList;
use App\Lists\AddFunds\Receipt\Status\AddFundsReceiptStatusListItem;
use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\Receipt\Transaction\AddFundsTransaction;
use App\Models\MySql\User\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Eloquent;

/**
 * App\Models\MySql\Receipt\AddFundsReceipt
 *
 * @property int $id
 * @property int $user_id
 * @property int $method_id
 * @property int $status_id
 * @property string|null $description
 * @property float $amount
 * @property float|null $amount_total
 * @property float|null $payment_fee
 * @property string|null $hash
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read string $full_id
 * @property-read PaymentMethod $method
 * @property-read Collection<int, AddFundsTransaction> $transactions
 * @property-read int|null $transactions_count
 * @property-read User $user
 * @method static Builder|AddFundsReceipt newModelQuery()
 * @method static Builder|AddFundsReceipt newQuery()
 * @method static Builder|AddFundsReceipt onlyTrashed()
 * @method static Builder|AddFundsReceipt query()
 * @method static Builder|AddFundsReceipt whereAmount($value)
 * @method static Builder|AddFundsReceipt whereAmountTotal($value)
 * @method static Builder|AddFundsReceipt whereCreatedAt($value)
 * @method static Builder|AddFundsReceipt whereDeletedAt($value)
 * @method static Builder|AddFundsReceipt whereDescription($value)
 * @method static Builder|AddFundsReceipt whereHash($value)
 * @method static Builder|AddFundsReceipt whereId($value)
 * @method static Builder|AddFundsReceipt whereMethodId($value)
 * @method static Builder|AddFundsReceipt wherePaymentFee($value)
 * @method static Builder|AddFundsReceipt whereStatusId($value)
 * @method static Builder|AddFundsReceipt whereUpdatedAt($value)
 * @method static Builder|AddFundsReceipt whereUserId($value)
 * @method static Builder|AddFundsReceipt withTrashed()
 * @method static Builder|AddFundsReceipt withoutTrashed()
 * @mixin Eloquent
 */
class AddFundsReceipt extends Model
{
    use SoftDeletes;

    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'add_funds_receipts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'method_id', 'status_id', 'description', 'amount',
        'amount_total', 'payment_fee', 'hash'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'hash'
    ];

    //--------------------------------------------------------------------------
    // Belongs to relations

    /**
     * @return BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function method() : BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    //--------------------------------------------------------------------------
    // Has many relations

    /**
     * @return HasMany
     */
    public function transactions() : HasMany
    {
        return $this->hasMany(
            AddFundsTransaction::class,
            'receipt_id',
            'id'
        );
    }

    //--------------------------------------------------------------------------
    // Lists accessors

    /**
     * @return AddFundsReceiptStatusListItem|null
     */
    public function getStatus() : ?AddFundsReceiptStatusListItem
    {
        return AddFundsReceiptStatusList::getItem(
            $this->status_id
        );
    }

    //--------------------------------------------------------------------------
    // Accessors

    /**
     * @return string
     */
    public function getFullIdAttribute() : string
    {
        return 'AF' . $this->id;
    }
}
