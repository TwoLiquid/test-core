<?php

namespace App\Models\MySql\Order;

use App\Lists\Invoice\Status\InvoiceStatusList;
use App\Lists\Invoice\Status\InvoiceStatusListItem;
use App\Lists\Invoice\Type\InvoiceTypeList;
use App\Lists\Invoice\Type\InvoiceTypeListItem;
use App\Models\MySql\Sale;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Eloquent;

/**
 * App\Models\MySql\Order\OrderInvoice
 *
 * @property int $id
 * @property int|null $parent_id
 * @property int $order_id
 * @property int $type_id
 * @property int $status_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read string $full_id
 * @property-read Collection<int, OrderItem> $items
 * @property-read int|null $items_count
 * @property-read Order $order
 * @property-read OrderInvoice|null $parent
 * @property-read Collection<int, OrderTransaction> $transactions
 * @property-read int|null $transactions_count
 * @method static Builder|OrderInvoice newModelQuery()
 * @method static Builder|OrderInvoice newQuery()
 * @method static Builder|OrderInvoice query()
 * @method static Builder|OrderInvoice whereCreatedAt($value)
 * @method static Builder|OrderInvoice whereDeletedAt($value)
 * @method static Builder|OrderInvoice whereId($value)
 * @method static Builder|OrderInvoice whereOrderId($value)
 * @method static Builder|OrderInvoice whereParentId($value)
 * @method static Builder|OrderInvoice whereStatusId($value)
 * @method static Builder|OrderInvoice whereTypeId($value)
 * @method static Builder|OrderInvoice whereUpdatedAt($value)
 * @mixin Eloquent
 */
class OrderInvoice extends Model
{
    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'order_invoices';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id', 'order_id', 'type_id', 'status_id'
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
    public function parent() : BelongsTo
    {
        return $this->belongsTo(OrderInvoice::class);
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
            'invoice_order_item',
            'invoice_id',
            'item_id'
        );
    }

    //--------------------------------------------------------------------------
    // Has many relations

    /**
     * @return HasMany
     */
    public function transactions() : HasMany
    {
        return $this->hasMany(
            OrderTransaction::class,
            'invoice_id'
        );
    }

    //--------------------------------------------------------------------------
    // Methods

    /**
     * @return Sale|null
     */
    public function getSale() : ?Sale
    {
        return Sale::query()->with([
            'items' => function ($query) {
                $query->whereIn(
                    'item_id',
                    $this->items
                        ->pluck('id')
                        ->values()
                        ->toArray()
                );
            }
        ])->first();
    }

    //--------------------------------------------------------------------------
    // Lists accessors

    /**
     * @return InvoiceTypeListItem|null
     */
    public function getType() : ?InvoiceTypeListItem
    {
        return InvoiceTypeList::getItem(
            $this->type_id
        );
    }

    /**
     * @return InvoiceStatusListItem|null
     */
    public function getStatus() : ?InvoiceStatusListItem
    {
        return InvoiceStatusList::getItem(
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
        return $this->getType()->idPrefix . $this->id;
    }
}
