<?php

namespace App\Models\MySql\Receipt;

use App\Lists\Withdrawal\Receipt\Status\WithdrawalReceiptStatusList;
use App\Lists\Withdrawal\Receipt\Status\WithdrawalReceiptStatusListItem;
use App\Models\MongoDb\WithdrawalRequest;
use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\Receipt\Transaction\WithdrawalTransaction;
use App\Models\MySql\User\User;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use MongoDB\Laravel\Eloquent\HybridRelations;
use Eloquent;

/**
 * App\Models\MySql\Receipt\WithdrawalReceipt
 *
 * @property int $id
 * @property int $user_id
 * @property int $method_id
 * @property int $status_id
 * @property string|null $description
 * @property float $amount
 * @property float|null $amount_total
 * @property float|null $payment_fee
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read string $full_id
 * @property-read PaymentMethod $method
 * @property-read WithdrawalRequest|null $request
 * @property-read Collection<int, WithdrawalTransaction> $transactions
 * @property-read int|null $transactions_count
 * @property-read User $user
 * @method static Builder|WithdrawalReceipt addHybridHas(Relation $relation, string $operator = '>=', string $count = 1, string $boolean = 'and', ?Closure $callback = null)
 * @method static Builder|WithdrawalReceipt newModelQuery()
 * @method static Builder|WithdrawalReceipt newQuery()
 * @method static Builder|WithdrawalReceipt onlyTrashed()
 * @method static Builder|WithdrawalReceipt query()
 * @method static Builder|WithdrawalReceipt whereAmount($value)
 * @method static Builder|WithdrawalReceipt whereAmountTotal($value)
 * @method static Builder|WithdrawalReceipt whereCreatedAt($value)
 * @method static Builder|WithdrawalReceipt whereDeletedAt($value)
 * @method static Builder|WithdrawalReceipt whereDescription($value)
 * @method static Builder|WithdrawalReceipt whereId($value)
 * @method static Builder|WithdrawalReceipt whereMethodId($value)
 * @method static Builder|WithdrawalReceipt wherePaymentFee($value)
 * @method static Builder|WithdrawalReceipt whereStatusId($value)
 * @method static Builder|WithdrawalReceipt whereUpdatedAt($value)
 * @method static Builder|WithdrawalReceipt whereUserId($value)
 * @method static Builder|WithdrawalReceipt withTrashed()
 * @method static Builder|WithdrawalReceipt withoutTrashed()
 * @mixin Eloquent
 */
class WithdrawalReceipt extends Model
{
    use SoftDeletes, HybridRelations;

    /**
     * Database connection type
     *
     * @var string
     */
    protected $connection = 'mysql';

    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'withdrawal_receipts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'method_id', 'status_id', 'description',
        'amount', 'amount_total', 'payment_fee'
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
            WithdrawalTransaction::class,
            'receipt_id',
            'id'
        );
    }

    //--------------------------------------------------------------------------
    // Has one relations

    /**
     * @return HasOne
     */
    public function request() : HasOne
    {
        return $this->hasOne(
            WithdrawalRequest::class,
            'receipt_id',
            'id'
        );
    }

    //--------------------------------------------------------------------------
    // Lists accessors

    /**
     * @return WithdrawalReceiptStatusListItem|null
     */
    public function getStatus() : ?WithdrawalReceiptStatusListItem
    {
        return WithdrawalReceiptStatusList::getItem(
            $this->status_id
        );
    }

    //--------------------------------------------------------------------------
    // Magic accessors

    /**
     * @return string
     */
    public function getFullIdAttribute() : string
    {
        return 'WR' . $this->id;
    }
}
