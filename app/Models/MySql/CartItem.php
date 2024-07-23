<?php

namespace App\Models\MySql;

use App\Models\MySql\User\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Eloquent;

/**
 * App\Models\MySql\CartItem
 *
 * @property int $id
 * @property int $user_id
 * @property int $appearance_case_id
 * @property int|null $timeslot_id
 * @property Carbon|null $datetime_from
 * @property Carbon|null $datetime_to
 * @property int $quantity
 * @property-read AppearanceCase $appearanceCase
 * @property-read Timeslot|null $timeslot
 * @property-read User $user
 * @method static Builder|CartItem newModelQuery()
 * @method static Builder|CartItem newQuery()
 * @method static Builder|CartItem query()
 * @method static Builder|CartItem whereAppearanceCaseId($value)
 * @method static Builder|CartItem whereDatetimeFrom($value)
 * @method static Builder|CartItem whereDatetimeTo($value)
 * @method static Builder|CartItem whereId($value)
 * @method static Builder|CartItem whereQuantity($value)
 * @method static Builder|CartItem whereTimeslotId($value)
 * @method static Builder|CartItem whereUserId($value)
 * @mixin Eloquent
 */
class CartItem extends Model
{
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
    protected $table = 'cart_items';

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
        'user_id', 'appearance_case_id', 'timeslot_id', 'datetime_from', 'datetime_to', 'quantity'
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
}
