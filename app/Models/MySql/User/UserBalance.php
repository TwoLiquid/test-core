<?php

namespace App\Models\MySql\User;

use App\Lists\User\Balance\Status\UserBalanceStatusList;
use App\Lists\User\Balance\Status\UserBalanceStatusListItem;
use App\Lists\User\Balance\Type\UserBalanceTypeList;
use App\Lists\User\Balance\Type\UserBalanceTypeListItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Eloquent;

/**
 * App\Models\MySql\User\UserBalance
 *
 * @property int $id
 * @property int $user_id
 * @property int $type_id
 * @property int $status_id
 * @property float $amount
 * @property float|null $pending_amount
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read string $full_id
 * @property-read User $user
 * @method static Builder|UserBalance newModelQuery()
 * @method static Builder|UserBalance newQuery()
 * @method static Builder|UserBalance onlyTrashed()
 * @method static Builder|UserBalance query()
 * @method static Builder|UserBalance whereAmount($value)
 * @method static Builder|UserBalance whereCreatedAt($value)
 * @method static Builder|UserBalance whereDeletedAt($value)
 * @method static Builder|UserBalance whereId($value)
 * @method static Builder|UserBalance wherePendingAmount($value)
 * @method static Builder|UserBalance whereStatusId($value)
 * @method static Builder|UserBalance whereTypeId($value)
 * @method static Builder|UserBalance whereUpdatedAt($value)
 * @method static Builder|UserBalance whereUserId($value)
 * @method static Builder|UserBalance withTrashed()
 * @method static Builder|UserBalance withoutTrashed()
 * @mixin Eloquent
 */
class UserBalance extends Model
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
    protected $table = 'user_balances';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'type_id', 'status_id', 'amount', 'pending_amount'
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

    //--------------------------------------------------------------------------
    // Accessors

    /**
     * @return UserBalanceTypeListItem|null
     */
    public function getType() : ?UserBalanceTypeListItem
    {
        return UserBalanceTypeList::getItem(
            $this->type_id
        );
    }

    /**
     * @return UserBalanceStatusListItem|null
     */
    public function getStatus() : ?UserBalanceStatusListItem
    {
        return UserBalanceStatusList::getItem(
            $this->status_id
        );
    }

    /**
     * @return string
     */
    public function getFullIdAttribute() : string
    {
        return $this->getType()->idPrefix . $this->id;
    }
}
