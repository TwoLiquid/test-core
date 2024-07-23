<?php

namespace App\Models\MongoDb\Order\Request;

use App\Lists\Order\Item\Request\Action\OrderItemRequestActionList;
use App\Lists\Order\Item\Request\Action\OrderItemRequestActionListItem;
use App\Lists\Order\Item\Request\Initiator\OrderItemRequestInitiatorList;
use App\Lists\Order\Item\Request\Initiator\OrderItemRequestInitiatorListItem;
use App\Lists\Order\Item\Request\Status\OrderItemRequestStatusList;
use App\Lists\Order\Item\Request\Status\OrderItemRequestStatusListItem;
use App\Lists\Order\Item\Status\OrderItemStatusList;
use App\Lists\Order\Item\Status\OrderItemStatusListItem;
use App\Models\MySql\Order\OrderItem;
use App\Models\MySql\User\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use MongoDB\Laravel\Eloquent\HybridRelations;
use MongoDB\Laravel\Eloquent\Model;

/**
 * App\Models\MongoDb\Order\Request\OrderItemFinishRequest
 *
 * @property string $_id
 * @property int $item_id
 * @property int $buyer_id
 * @property int $seller_id
 * @property int $opening_id
 * @property int $closing_id
 * @property int $initiator_id
 * @property int $from_order_item_status_id
 * @property int $to_order_item_status_id
 * @property int $action_id
 * @property int $request_status_id
 * @property Carbon $from_request_datetime
 * @property Carbon $to_request_datetime
 * @property Carbon $waiting
 * @property-read OrderItem $item
 * @property-read User $buyer
 * @property-read User $seller
 * @property-read User $opening
 * @property-read User $closing
 * @method static Builder|OrderItemFinishRequest find(string $id)
 * @method static Builder|OrderItemFinishRequest query()
 */
class OrderItemFinishRequest extends Model
{
    use HybridRelations;

    /**
     * Database connection type
     *
     * @var string
     */
    protected $connection = 'mongodb';

    /**
     * Database collection name
     *
     * @var string
     */
    protected $collection = 'order_item_finish_requests';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_id', 'buyer_id', 'seller_id', 'opening_id', 'closing_id', 'initiator_id',
        'from_order_item_status_id', 'to_order_item_status_id', 'action_id',
        'request_status_id', 'from_request_datetime', 'to_request_datetime'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'from_request_datetime' => 'datetime',
        'to_request_datetime'   => 'datetime'
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

    /**
     * @return BelongsTo
     */
    public function opening() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function closing() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    //--------------------------------------------------------------------------
    // Magic accessors

    /**
     * @return string
     */
    public function getWaitingAttribute() : string
    {
        return $this->to_request_datetime ?
            getWaitingFromDates($this->from_request_datetime, $this->to_request_datetime) :
            getWaitingFromDates($this->from_request_datetime, Carbon::now());
    }

    //--------------------------------------------------------------------------
    // Lists accessors

    /**
     * @return OrderItemRequestInitiatorListItem|null
     */
    public function getInitiator() : ?OrderItemRequestInitiatorListItem
    {
        return OrderItemRequestInitiatorList::getItem(
            $this->initiator_id
        );
    }

    /**
     * @return OrderItemStatusListItem|null
     */
    public function getFromOrderItemStatus() : ?OrderItemStatusListItem
    {
        return OrderItemStatusList::getItem(
            $this->from_order_item_status_id
        );
    }

    /**
     * @return OrderItemStatusListItem|null
     */
    public function getToOrderItemStatus() : ?OrderItemStatusListItem
    {
        return OrderItemStatusList::getItem(
            $this->to_order_item_status_id
        );
    }

    /**
     * @return OrderItemRequestActionListItem|null
     */
    public function getAction() : ?OrderItemRequestActionListItem
    {
        return OrderItemRequestActionList::getItem(
            $this->action_id
        );
    }

    /**
     * @return OrderItemRequestStatusListItem|null
     */
    public function getRequestStatus() : ?OrderItemRequestStatusListItem
    {
        return OrderItemRequestStatusList::getItem(
            $this->request_status_id
        );
    }
}
