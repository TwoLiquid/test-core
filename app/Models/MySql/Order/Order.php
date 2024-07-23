<?php

namespace App\Models\MySql\Order;

use App\Lists\Invoice\Type\InvoiceTypeList;
use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\Sale;
use App\Models\MySql\User\User;
use Database\Factories\MySql\Order\OrderFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Eloquent;

/**
 * App\Models\MySql\Order\Order
 *
 * @property int $id
 * @property int $buyer_id
 * @property int|null $method_id
 * @property float|null $amount
 * @property float|null $amount_tax
 * @property float|null $amount_total
 * @property float|null $payment_fee
 * @property float|null $payment_fee_tax
 * @property string|null $hash
 * @property Carbon|null $paid_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read User $buyer
 * @property-read string $full_id
 * @property-read Collection<int, OrderInvoice> $invoices
 * @property-read int|null $invoices_count
 * @property-read Collection<int, OrderItem> $items
 * @property-read int|null $items_count
 * @property-read PaymentMethod|null $method
 * @property-read Collection<int, Sale> $sales
 * @property-read int|null $sales_count
 * @method static Builder|Order newModelQuery()
 * @method static Builder|Order newQuery()
 * @method static Builder|Order onlyTrashed()
 * @method static Builder|Order query()
 * @method static Builder|Order whereAmount($value)
 * @method static Builder|Order whereAmountTax($value)
 * @method static Builder|Order whereAmountTotal($value)
 * @method static Builder|Order whereBuyerId($value)
 * @method static Builder|Order whereCreatedAt($value)
 * @method static Builder|Order whereDeletedAt($value)
 * @method static Builder|Order whereHash($value)
 * @method static Builder|Order whereId($value)
 * @method static Builder|Order whereMethodId($value)
 * @method static Builder|Order wherePaidAt($value)
 * @method static Builder|Order wherePaymentFee($value)
 * @method static Builder|Order wherePaymentFeeTax($value)
 * @method static Builder|Order whereUpdatedAt($value)
 * @method static Builder|Order withTrashed()
 * @method static Builder|Order withoutTrashed()
 * @method static OrderFactory factory($count = null, $state = [])
 * @mixin Eloquent
 */
class Order extends Model
{
    use SoftDeletes, HasFactory;

    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'buyer_id', 'method_id', 'amount', 'amount_tax', 'amount_total',
        'payment_fee', 'payment_fee_tax', 'hash', 'paid_at'
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
    public function buyer() : BelongsTo
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
    public function items() : HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * @return HasMany
     */
    public function invoices() : HasMany
    {
        return $this->hasMany(OrderInvoice::class);
    }

    /**
     * @return HasMany
     */
    public function sales() : HasMany
    {
        return $this->hasMany(Sale::class);
    }

    //--------------------------------------------------------------------------
    // Methods

    /**
     * @return OrderInvoice|null
     */
    public function getBuyerInvoice() : ?OrderInvoice
    {
        return $this->invoices
            ->where('type_id', '=', InvoiceTypeList::getBuyer()->id)
            ->first();
    }

    //--------------------------------------------------------------------------
    // Accessors

    /**
     * @return string
     */
    public function getFullIdAttribute() : string
    {
        return 'OR' . $this->id;
    }
}
