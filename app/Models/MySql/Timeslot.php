<?php

namespace App\Models\MySql;

use App\Models\MySql\Order\OrderItem;
use App\Models\MySql\User\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Eloquent;

/**
 * App\Models\MySql\Timeslot
 *
 * @property int $id
 * @property Carbon|null $datetime_from
 * @property Carbon|null $datetime_to
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, OrderItem> $orderItems
 * @property-read int|null $order_items_count
 * @property-read Collection<int, User> $users
 * @property-read int|null $users_count
 * @method static Builder|Timeslot newModelQuery()
 * @method static Builder|Timeslot newQuery()
 * @method static Builder|Timeslot onlyTrashed()
 * @method static Builder|Timeslot query()
 * @method static Builder|Timeslot whereDatetimeFrom($value)
 * @method static Builder|Timeslot whereDatetimeTo($value)
 * @method static Builder|Timeslot whereDeletedAt($value)
 * @method static Builder|Timeslot whereId($value)
 * @method static Builder|Timeslot withTrashed()
 * @method static Builder|Timeslot withoutTrashed()
 * @mixin Eloquent
 */
class Timeslot extends Model
{
    use SoftDeletes;

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
    protected $table = 'timeslots';

    /**
     * Provide default timestamps usage
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'datetime_from', 'datetime_to'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'datetime_from' => 'datetime',
        'datetime_to'   => 'datetime'
    ];

    //--------------------------------------------------------------------------
    // Belongs to many relations

    /**
     * @return BelongsToMany
     */
    public function users() : BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    //--------------------------------------------------------------------------
    // Has many relations

    /**
     * @return HasMany
     */
    public function orderItems() : HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
