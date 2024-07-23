<?php

namespace App\Models\MySql\Order;

use App\Lists\Order\Item\Payment\Status\OrderItemPaymentStatusList;
use App\Lists\Order\Item\Payment\Status\OrderItemPaymentStatusListItem;
use App\Lists\Order\Item\Status\OrderItemStatusList;
use App\Lists\Order\Item\Status\OrderItemStatusListItem;
use App\Models\MongoDb\Order\Request\OrderItemFinishRequest;
use App\Models\MongoDb\Order\Request\OrderItemPendingRequest;
use App\Models\MongoDb\Order\Request\OrderItemRescheduleRequest;
use App\Models\MySql\AppearanceCase;
use App\Models\MySql\Timeslot;
use App\Models\MySql\Tip\Tip;
use App\Models\MySql\User\User;
use App\Models\MySql\Vybe\Vybe;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use MongoDB\Laravel\Eloquent\HybridRelations;
use Eloquent;

/**
 * App\Models\MySql\Order\OrderItem
 *
 * @property int $id
 * @property int $order_id
 * @property int $vybe_id
 * @property int $seller_id
 * @property int $appearance_case_id
 * @property int $timeslot_id
 * @property int $status_id
 * @property int|null $previous_status_id
 * @property int $payment_status_id
 * @property int $vybe_version
 * @property float $price
 * @property int $quantity
 * @property float|null $handling_fee
 * @property float $amount_earned
 * @property float|null $amount_tax
 * @property float $amount_total
 * @property Carbon|null $expired_at
 * @property Carbon|null $accepted_at
 * @property Carbon|null $finished_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read AppearanceCase $appearanceCase
 * @property-read Collection<int, OrderItemFinishRequest> $finishRequests
 * @property-read int|null $finish_requests_count
 * @property-read string $full_id
 * @property-read Collection<int, OrderInvoice> $invoices
 * @property-read int|null $invoices_count
 * @property-read Order $order
 * @property-read Collection<int, OrderItemPendingRequest> $pendingRequests
 * @property-read int|null $pending_requests_count
 * @property-read Collection<int, OrderItemRescheduleRequest> $rescheduleRequests
 * @property-read int|null $reschedule_requests_count
 * @property-read User $seller
 * @property-read Timeslot $timeslot
 * @property-read Collection<int, Tip> $tips
 * @property-read int|null $tips_count
 * @property-read Vybe $vybe
 * @method static Builder|OrderItem addHybridHas(Relation $relation, string $operator = '>=', string $count = 1, string $boolean = 'and', ?Closure $callback = null)
 * @method static Builder|OrderItem newModelQuery()
 * @method static Builder|OrderItem newQuery()
 * @method static Builder|OrderItem onlyTrashed()
 * @method static Builder|OrderItem query()
 * @method static Builder|OrderItem whereAcceptedAt($value)
 * @method static Builder|OrderItem whereAmountEarned($value)
 * @method static Builder|OrderItem whereAmountTax($value)
 * @method static Builder|OrderItem whereAmountTotal($value)
 * @method static Builder|OrderItem whereAppearanceCaseId($value)
 * @method static Builder|OrderItem whereCreatedAt($value)
 * @method static Builder|OrderItem whereDeletedAt($value)
 * @method static Builder|OrderItem whereExpiredAt($value)
 * @method static Builder|OrderItem whereFinishedAt($value)
 * @method static Builder|OrderItem whereHandlingFee($value)
 * @method static Builder|OrderItem whereId($value)
 * @method static Builder|OrderItem whereOrderId($value)
 * @method static Builder|OrderItem wherePaymentStatusId($value)
 * @method static Builder|OrderItem wherePreviousStatusId($value)
 * @method static Builder|OrderItem wherePrice($value)
 * @method static Builder|OrderItem whereQuantity($value)
 * @method static Builder|OrderItem whereSellerId($value)
 * @method static Builder|OrderItem whereStatusId($value)
 * @method static Builder|OrderItem whereTimeslotId($value)
 * @method static Builder|OrderItem whereUpdatedAt($value)
 * @method static Builder|OrderItem whereVybeId($value)
 * @method static Builder|OrderItem whereVybeVersion($value)
 * @method static Builder|OrderItem withTrashed()
 * @method static Builder|OrderItem withoutTrashed()
 * @mixin Eloquent
 */
class OrderItem extends Model
{
    use HybridRelations, SoftDeletes;

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
    protected $table = 'order_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id', 'vybe_id', 'seller_id', 'appearance_case_id', 'timeslot_id', 'status_id',
        'previous_status_id', 'payment_status_id', 'vybe_version', 'price', 'handling_fee',
        'quantity', 'amount_earned', 'amount_tax', 'amount_total',
        'expired_at', 'accepted_at', 'finished_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'expired_at'  => 'datetime',
        'accepted_at' => 'datetime',
        'finished_at' => 'datetime'
    ];

    //--------------------------------------------------------------------------
    // Belongs to relations

    /**
     * @return BelongsTo
     */
    public function order() : BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return BelongsTo
     */
    public function vybe() : BelongsTo
    {
        return $this->belongsTo(Vybe::class);
    }

    /**
     * @return BelongsTo
     */
    public function seller() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function appearanceCase() : BelongsTo
    {
        return $this->belongsTo(AppearanceCase::class);
    }

    /**
     * @return BelongsTo
     */
    public function timeslot() : BelongsTo
    {
        return $this->belongsTo(Timeslot::class);
    }

    //--------------------------------------------------------------------------
    // Belongs to many relations

    /**
     * @return BelongsToMany
     */
    public function invoices() : BelongsToMany
    {
        return $this->belongsToMany(
            OrderInvoice::class,
            'invoice_order_item',
            'item_id',
            'invoice_id'
        );
    }

    //--------------------------------------------------------------------------
    // Has many relations

    /**
     * @return HasMany
     */
    public function tips() : HasMany
    {
        return $this->hasMany(
            Tip::class,
            'item_id',
            'id'
        );
    }

    /**
     * @return HasMany
     */
    public function pendingRequests() : HasMany
    {
        return $this->hasMany(
            OrderItemPendingRequest::class,
            'item_id',
            'id'
        );
    }

    /**
     * @return HasMany
     */
    public function rescheduleRequests() : HasMany
    {
        return $this->hasMany(
            OrderItemRescheduleRequest::class,
            'item_id',
            'id'
        );
    }

    /**
     * @return HasMany
     */
    public function finishRequests() : HasMany
    {
        return $this->hasMany(
            OrderItemFinishRequest::class,
            'item_id',
            'id'
        );
    }

    //--------------------------------------------------------------------------
    // Methods

    /**
     * @return float
     */
    public function getTotalAmountEarned() : float
    {
        if (!$this->vybe
            ->getType()
            ->isSolo()
        ) {
            return round(
                $this->amount_earned * $this->timeslot->users_count,
                2
            );
        }

        return $this->amount_earned;
    }

    /**
     * @return OrderItemPendingRequest|null
     */
    public function getOpenedPendingRequest() : ?OrderItemPendingRequest
    {
        return $this->pendingRequests
            ->whereNull('to_order_item_status_id')
            ->whereNull('closing_id')
            ->whereNull('action_id')
            ->first();
    }

    /**
     * @return OrderItemRescheduleRequest|null
     */
    public function getOpenedRescheduleRequest() : ?OrderItemRescheduleRequest
    {
        return $this->rescheduleRequests
            ->whereNull('to_order_item_status_id')
            ->whereNull('closing_id')
            ->whereNull('action_id')
            ->first();
    }

    /**
     * @return OrderItemFinishRequest|null
     */
    public function getOpenedFinishRequest() : ?OrderItemFinishRequest
    {
        return $this->finishRequests
            ->whereNull('to_order_item_status_id')
            ->whereNull('closing_id')
            ->whereNull('action_id')
            ->first();
    }

    //--------------------------------------------------------------------------
    // Accessors

    /**
     * @return OrderItemStatusListItem|null
     */
    public function getStatus() : ?OrderItemStatusListItem
    {
        return OrderItemStatusList::getItem(
            $this->status_id
        );
    }

    /**
     * @return OrderItemStatusListItem|null
     */
    public function getPreviousStatus() : ?OrderItemStatusListItem
    {
        return OrderItemStatusList::getItem(
            $this->previous_status_id
        );
    }

    /**
     * @return OrderItemPaymentStatusListItem|null
     */
    public function getPaymentStatus() : ?OrderItemPaymentStatusListItem
    {
        return OrderItemPaymentStatusList::getItem(
            $this->payment_status_id
        );
    }

    /**
     * @return string
     */
    public function getFullIdAttribute() : string
    {
        return 'OR' . $this->order_id . '-' . $this->id;
    }
}
