<?php

namespace App\Models\MySql;

use App\Models\MySql\Order\Order;
use App\Models\MySql\Order\OrderItem;
use App\Models\MySql\User\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Eloquent;

/**
 * App\Models\MySql\Sale
 *
 * @property int $id
 * @property int $order_id
 * @property int $seller_id
 * @property float|null $amount_earned
 * @property float|null $amount_total
 * @property float|null $handling_fee
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read string $full_id
 * @property-read Collection<int, OrderItem> $items
 * @property-read int|null $items_count
 * @property-read Order $order
 * @property-read User $seller
 * @method static Builder|Sale newModelQuery()
 * @method static Builder|Sale newQuery()
 * @method static Builder|Sale query()
 * @method static Builder|Sale whereAmountEarned($value)
 * @method static Builder|Sale whereAmountTotal($value)
 * @method static Builder|Sale whereCreatedAt($value)
 * @method static Builder|Sale whereDeletedAt($value)
 * @method static Builder|Sale whereHandlingFee($value)
 * @method static Builder|Sale whereId($value)
 * @method static Builder|Sale whereOrderId($value)
 * @method static Builder|Sale whereSellerId($value)
 * @method static Builder|Sale whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Sale extends Model
{
    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'sales';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id', 'seller_id', 'amount_earned', 'amount_total', 'handling_fee'
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
    public function seller() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    //--------------------------------------------------------------------------
    // Belongs to many relations

    /**
     * @return BelongsToMany
     */
    public function items() : BelongsToMany
    {
        return $this->belongsToMany(
            OrderItem::class,
            'order_item_sale',
            'sale_id',
            'item_id'
        );
    }

    //--------------------------------------------------------------------------
    // Accessors

    /**
     * @return string
     */
    public function getFullIdAttribute() : string
    {
        return 'S' . $this->id;
    }
}
