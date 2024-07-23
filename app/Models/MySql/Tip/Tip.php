<?php

namespace App\Models\MySql\Tip;

use App\Lists\Invoice\Type\InvoiceTypeList;
use App\Models\MySql\Order\OrderItem;
use App\Models\MySql\Payment\PaymentMethod;
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
 * App\Models\MySql\Tip\Tip
 *
 * @property int $id
 * @property int $item_id
 * @property int $method_id
 * @property int $buyer_id
 * @property int $seller_id
 * @property float|null $amount
 * @property float|null $amount_earned
 * @property float|null $amount_tax
 * @property float|null $amount_total
 * @property float|null $handling_fee
 * @property float|null $payment_fee
 * @property float|null $payment_fee_tax
 * @property string|null $comment
 * @property string|null $hash
 * @property Carbon|null $paid_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read User $buyer
 * @property-read Collection<int, TipInvoice> $invoices
 * @property-read int|null $invoices_count
 * @property-read OrderItem $item
 * @property-read PaymentMethod $method
 * @property-read User $seller
 * @property-read Collection<int, TipTransaction> $transactions
 * @property-read int|null $transactions_count
 * @method static Builder|Tip newModelQuery()
 * @method static Builder|Tip newQuery()
 * @method static Builder|Tip onlyTrashed()
 * @method static Builder|Tip query()
 * @method static Builder|Tip whereAmount($value)
 * @method static Builder|Tip whereAmountEarned($value)
 * @method static Builder|Tip whereAmountTax($value)
 * @method static Builder|Tip whereAmountTotal($value)
 * @method static Builder|Tip whereBuyerId($value)
 * @method static Builder|Tip whereComment($value)
 * @method static Builder|Tip whereCreatedAt($value)
 * @method static Builder|Tip whereDeletedAt($value)
 * @method static Builder|Tip whereHandlingFee($value)
 * @method static Builder|Tip whereHash($value)
 * @method static Builder|Tip whereId($value)
 * @method static Builder|Tip whereItemId($value)
 * @method static Builder|Tip whereMethodId($value)
 * @method static Builder|Tip wherePaidAt($value)
 * @method static Builder|Tip wherePaymentFee($value)
 * @method static Builder|Tip wherePaymentFeeTax($value)
 * @method static Builder|Tip whereSellerId($value)
 * @method static Builder|Tip whereUpdatedAt($value)
 * @method static Builder|Tip withTrashed()
 * @method static Builder|Tip withoutTrashed()
 * @mixin Eloquent
 */
class Tip extends Model
{
    use SoftDeletes;

    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'tips';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_id', 'method_id', 'buyer_id', 'seller_id', 'amount', 'amount_earned',
        'amount_tax', 'amount_total', 'handling_fee', 'payment_fee',
        'payment_fee_tax', 'comment', 'hash', 'paid_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'hash'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'paid_at' => 'datetime'
    ];

    //--------------------------------------------------------------------------
    // Belongs to relations

    /**
     * @return BelongsTo
     */
    public function item() : BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    /**
     * @return BelongsTo
     */
    public function method() : BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    /**
     * @return BelongsTo
     */
    public function buyer() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function seller() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    //--------------------------------------------------------------------------
    // Has many relations

    /**
     * @return HasMany
     */
    public function invoices() : HasMany
    {
        return $this->hasMany(TipInvoice::class);
    }

    /**
     * @return HasMany
     */
    public function transactions() : HasMany
    {
        return $this->hasMany(TipTransaction::class);
    }

    //--------------------------------------------------------------------------
    // Methods

    /**
     * @return TipInvoice
     */
    public function getBuyerInvoice() : TipInvoice
    {
        return $this->invoices
            ->where('type_id', '=', InvoiceTypeList::getTipBuyer()->id)
            ->first();
    }

    /**
     * @return TipInvoice
     */
    public function getSellerInvoice() : TipInvoice
    {
        return $this->invoices
            ->where('type_id', '=', InvoiceTypeList::getTipSeller()->id)
            ->first();
    }
}
