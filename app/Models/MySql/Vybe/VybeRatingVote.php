<?php

namespace App\Models\MySql\Vybe;

use App\Models\MySql\User\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Eloquent;

/**
 * App\Models\MySql\Vybe\VybeRatingVote
 *
 * @property int $id
 * @property int $vybe_id
 * @property int $user_id
 * @property int $rating
 * @property-read User $user
 * @property-read Vybe $vybe
 * @method static Builder|VybeRatingVote newModelQuery()
 * @method static Builder|VybeRatingVote newQuery()
 * @method static Builder|VybeRatingVote query()
 * @method static Builder|VybeRatingVote whereId($value)
 * @method static Builder|VybeRatingVote whereRating($value)
 * @method static Builder|VybeRatingVote whereUserId($value)
 * @method static Builder|VybeRatingVote whereVybeId($value)
 * @mixin Eloquent
 */
class VybeRatingVote extends Model
{
    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'vybe_rating_votes';

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
        'vybe_id', 'user_id', 'rating'
    ];

    //--------------------------------------------------------------------------
    // Belongs to relations

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
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
