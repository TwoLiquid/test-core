<?php

namespace App\Models\MySql;

use App\Models\MySql\Activity\Activity;
use App\Models\MySql\Vybe\Vybe;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Eloquent;

/**
 * App\Models\MySql\Platform
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property bool $voice_chat
 * @property bool $visible_in_voice_chat
 * @property bool $video_chat
 * @property bool $visible_in_video_chat
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 * @property-read Collection<int, AppearanceCase> $appearanceCases
 * @property-read int|null $appearance_cases_count
 * @property-read Collection<int, Vybe> $vybes
 * @property-read int|null $vybes_count
 * @method static Builder|Platform newModelQuery()
 * @method static Builder|Platform newQuery()
 * @method static Builder|Platform onlyTrashed()
 * @method static Builder|Platform query()
 * @method static Builder|Platform whereCode($value)
 * @method static Builder|Platform whereDeletedAt($value)
 * @method static Builder|Platform whereId($value)
 * @method static Builder|Platform whereName($value)
 * @method static Builder|Platform whereVideoChat($value)
 * @method static Builder|Platform whereVisibleInVideoChat($value)
 * @method static Builder|Platform whereVisibleInVoiceChat($value)
 * @method static Builder|Platform whereVoiceChat($value)
 * @method static Builder|Platform withTrashed()
 * @method static Builder|Platform withoutTrashed()
 * @mixin Eloquent
 */
class Platform extends Model
{
    use HasRelationships, SoftDeletes;

    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'platforms';

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
        'name', 'code', 'voice_chat', 'visible_in_voice_chat',
        'video_chat', 'visible_in_video_chat'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'voice_chat'            => 'boolean',
        'visible_in_voice_chat' => 'boolean',
        'video_chat'            => 'boolean',
        'visible_in_video_chat' => 'boolean'
    ];

    //--------------------------------------------------------------------------
    // Belongs to many relations

    /**
     * @return BelongsToMany
     */
    public function activities() : BelongsToMany
    {
        return $this->belongsToMany(
            Activity::class
        );
    }

    /**
     * @return BelongsToMany
     */
    public function appearanceCases() : BelongsToMany
    {
        return $this->belongsToMany(
            AppearanceCase::class,
            'appearance_case_platform',
            'platform_id',
            'appearance_case_id'
        )->using(
            AppearanceCasePlatform::class
        );
    }

    //--------------------------------------------------------------------------
    // Has many deep from relations

    /**
     * @return HasManyDeep
     */
    public function vybes() : HasManyDeep
    {
        return $this->hasManyDeepFromRelations(
            $this->appearanceCases(),
            (new AppearanceCase())->vybe()
        )->distinct();
    }
}
