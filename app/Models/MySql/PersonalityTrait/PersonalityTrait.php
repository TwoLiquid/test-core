<?php

namespace App\Models\MySql\PersonalityTrait;

use App\Lists\PersonalityTrait\PersonalityTraitList;
use App\Lists\PersonalityTrait\PersonalityTraitListItem;
use App\Models\MySql\User\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Eloquent;

/**
 * App\Models\MySql\PersonalityTrait\PersonalityTrait
 *
 * @property int $id
 * @property int $user_id
 * @property int $trait_id
 * @property int $votes
 * @property-read User $user
 * @property-read Collection<int, PersonalityTraitVote> $voters
 * @property-read int|null $voters_count
 * @method static Builder|PersonalityTrait newModelQuery()
 * @method static Builder|PersonalityTrait newQuery()
 * @method static Builder|PersonalityTrait query()
 * @method static Builder|PersonalityTrait whereId($value)
 * @method static Builder|PersonalityTrait whereTraitId($value)
 * @method static Builder|PersonalityTrait whereUserId($value)
 * @method static Builder|PersonalityTrait whereVotes($value)
 * @mixin Eloquent
 */
class PersonalityTrait extends Model
{
    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'personality_traits';

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
        'user_id', 'trait_id', 'votes'
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
    // Has many relations

    /**
     * @return HasMany
     */
    public function voters() : HasMany
    {
        return $this->hasMany(
            PersonalityTraitVote::class,
            'personality_trait_id',
            'id'
        );
    }

    //--------------------------------------------------------------------------
    // Lists accessors

    /**
     * @return PersonalityTraitListItem|null
     */
    public function getTrait() : ?PersonalityTraitListItem
    {
        return PersonalityTraitList::getItem(
            $this->trait_id
        );
    }
}
