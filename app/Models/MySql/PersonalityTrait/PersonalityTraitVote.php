<?php

namespace App\Models\MySql\PersonalityTrait;

use App\Models\MySql\User\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Eloquent;

/**
 * App\Models\MySql\PersonalityTrait\PersonalityTraitVote
 *
 * @property int $id
 * @property int $personality_trait_id
 * @property int $voter_id
 * @property-read PersonalityTrait $personalityTrait
 * @property-read User $voter
 * @method static Builder|PersonalityTraitVote newModelQuery()
 * @method static Builder|PersonalityTraitVote newQuery()
 * @method static Builder|PersonalityTraitVote query()
 * @method static Builder|PersonalityTraitVote whereId($value)
 * @method static Builder|PersonalityTraitVote wherePersonalityTraitId($value)
 * @method static Builder|PersonalityTraitVote whereVoterId($value)
 * @mixin Eloquent
 */
class PersonalityTraitVote extends Model
{
    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'personality_trait_votes';

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
        'personality_trait_id', 'voter_id'
    ];

    //--------------------------------------------------------------------------
    // Belongs to relations

    /**
     * @return BelongsTo
     */
    public function personalityTrait() : BelongsTo
    {
        return $this->belongsTo(PersonalityTrait::class);
    }

    /**
     * @return BelongsTo
     */
    public function voter() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
