<?php

namespace App\Models\MySql\Vybe;

use App\Lists\Vybe\Access\VybeAccessList;
use App\Lists\Vybe\Access\VybeAccessListItem;
use App\Lists\Vybe\OrderAccept\VybeOrderAcceptList;
use App\Lists\Vybe\OrderAccept\VybeOrderAcceptListItem;
use App\Lists\Vybe\AgeLimit\VybeAgeLimitList;
use App\Lists\Vybe\AgeLimit\VybeAgeLimitListItem;
use App\Lists\Vybe\Period\VybePeriodList;
use App\Lists\Vybe\Period\VybePeriodListItem;
use App\Lists\Vybe\Showcase\VybeShowcaseList;
use App\Lists\Vybe\Showcase\VybeShowcaseListItem;
use App\Lists\Vybe\Status\VybeStatusList;
use App\Lists\Vybe\Status\VybeStatusListItem;
use App\Lists\Vybe\Step\VybeStepList;
use App\Lists\Vybe\Step\VybeStepListItem;
use App\Lists\Vybe\Type\VybeTypeList;
use App\Lists\Vybe\Type\VybeTypeListItem;
use App\Models\MongoDb\Vybe\Request\Change\VybeChangeRequest;
use App\Models\MongoDb\Vybe\Request\Deletion\VybeDeletionRequest;
use App\Models\MongoDb\Vybe\Request\Publish\VybePublishRequest;
use App\Models\MongoDb\Vybe\Request\Unpublish\VybeUnpublishRequest;
use App\Models\MongoDb\Vybe\Request\Unsuspend\VybeUnsuspendRequest;
use App\Models\MongoDb\Vybe\VybeSupport;
use App\Models\MongoDb\Vybe\VybeVersion;
use App\Models\MySql\Activity\Activity;
use App\Models\MySql\AppearanceCase;
use App\Models\MySql\Category;
use App\Models\MySql\Device;
use App\Models\MySql\Order\OrderItem;
use App\Models\MySql\Platform;
use App\Models\MySql\Schedule;
use App\Models\MySql\Timeslot;
use App\Models\MySql\User\User;
use Closure;
use Database\Factories\MySql\Vybe\VybeFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use MongoDB\Laravel\Relations\HasMany as MongoHasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use MongoDB\Laravel\Eloquent\HybridRelations;
use MongoDB\Laravel\Relations\HasOne;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Eloquent;

/**
 * App\Models\MySql\Vybe\Vybe
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $activity_id
 * @property int|null $type_id
 * @property int|null $period_id
 * @property int|null $access_id
 * @property int|null $showcase_id
 * @property int|null $status_id
 * @property int|null $age_limit_id
 * @property int|null $order_accept_id
 * @property int $step_id
 * @property string|null $access_password
 * @property int $version
 * @property string|null $title
 * @property int|null $user_count
 * @property int|null $order_advance
 * @property float|null $rating
 * @property int|null $rating_votes
 * @property string|null $suspend_reason
 * @property array|null $images_ids
 * @property array|null $videos_ids
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Activity|null $activity
 * @property-read Collection<int, AppearanceCase> $appearanceCases
 * @property-read int|null $appearance_cases_count
 * @property-read VybeChangeRequest|null $changeRequest
 * @property-read Collection<int, VybeChangeRequest> $changeRequests
 * @property-read int|null $change_requests_count
 * @property-read VybeDeletionRequest|null $deletionRequest
 * @property-read Collection<int, VybeDeletionRequest> $deletionRequests
 * @property-read int|null $deletion_requests_count
 * @property-read Collection<int, Device> $devices
 * @property-read int|null $devices_count
 * @property-read string|null $activity_suggestion
 * @property-read Category|null $category
 * @property-read string|null $category_suggestion
 * @property-read string|null $device_suggestion
 * @property-read string $full_id
 * @property-read string $full_version
 * @property-read Category|null $subcategory
 * @property-read string|null $subcategory_suggestion
 * @property-read Collection<int, OrderItem> $orderItems
 * @property-read int|null $order_items_count
 * @property-read VybePublishRequest|null $publishRequest
 * @property-read Collection<int, VybePublishRequest> $publishRequests
 * @property-read int|null $publish_requests_count
 * @property-read Collection<int, VybeRatingVote> $ratingVotes
 * @property-read int|null $rating_votes_count
 * @property-read Collection<int, Schedule> $schedules
 * @property-read int|null $schedules_count
 * @property-read VybeSupport|null $support
 * @property-read Collection<int, Timeslot> $timeslots
 * @property-read int|null $timeslots_count
 * @property-read VybeUnpublishRequest|null $unpublishRequest
 * @property-read Collection<int, VybeUnpublishRequest> $unpublishRequests
 * @property-read int|null $unpublish_requests_count
 * @property-read VybeUnsuspendRequest|null $unsuspendRequest
 * @property-read Collection<int, VybeUnsuspendRequest> $unsuspendRequests
 * @property-read int|null $unsuspend_requests_count
 * @property-read User $user
 * @property-read Collection<int, Vybe> $userFavorites
 * @property-read int|null $user_favorites_count
 * @property-read Collection<int, VybeVersion> $versions
 * @property-read int|null $versions_count
 * @method static Builder|Vybe addHybridHas(Relation $relation, string $operator = '>=', string $count = 1, string $boolean = 'and', ?Closure $callback = null)
 * @method static Builder|Vybe newModelQuery()
 * @method static Builder|Vybe newQuery()
 * @method static Builder|Vybe onlyTrashed()
 * @method static Builder|Vybe query()
 * @method static Builder|Vybe whereAccessId($value)
 * @method static Builder|Vybe whereAccessPassword($value)
 * @method static Builder|Vybe whereActivityId($value)
 * @method static Builder|Vybe whereAgeLimitId($value)
 * @method static Builder|Vybe whereCreatedAt($value)
 * @method static Builder|Vybe whereDeletedAt($value)
 * @method static Builder|Vybe whereId($value)
 * @method static Builder|Vybe whereImagesIds($value)
 * @method static Builder|Vybe whereOrderAcceptId($value)
 * @method static Builder|Vybe whereOrderAdvance($value)
 * @method static Builder|Vybe wherePeriodId($value)
 * @method static Builder|Vybe whereRating($value)
 * @method static Builder|Vybe whereRatingVotes($value)
 * @method static Builder|Vybe whereShowcaseId($value)
 * @method static Builder|Vybe whereStatusId($value)
 * @method static Builder|Vybe whereStepId($value)
 * @method static Builder|Vybe whereSuspendReason($value)
 * @method static Builder|Vybe whereTitle($value)
 * @method static Builder|Vybe whereTypeId($value)
 * @method static Builder|Vybe whereUpdatedAt($value)
 * @method static Builder|Vybe whereUserCount($value)
 * @method static Builder|Vybe whereUserId($value)
 * @method static Builder|Vybe whereVersion($value)
 * @method static Builder|Vybe whereVideosIds($value)
 * @method static Builder|Vybe withTrashed()
 * @method static Builder|Vybe withoutTrashed()
 * @method static VybeFactory factory($count = null, $state = [])
 * @property-read Collection|Platform[] $platforms
 * @property-read int|null $platforms_count
 * @mixin Eloquent
 */
class Vybe extends Model
{
    use HasRelationships, HybridRelations, SoftDeletes, HasFactory;

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
    protected $table = 'vybes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'activity_id', 'type_id', 'period_id', 'access_id', 'access_password', 'showcase_id',
        'status_id', 'age_limit_id', 'order_accept_id', 'step_id', 'title', 'version', 'user_count',
        'order_advance', 'rating', 'rating_votes', 'suspend_reason', 'images_ids', 'videos_ids'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'images_ids' => 'array',
        'videos_ids' => 'array'
    ];

    //--------------------------------------------------------------------------
    // Belongs to relations

    /**
     * @return BelongsTo
     */
    public function activity() : BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    /**
     * @return BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    //--------------------------------------------------------------------------
    // Belongs to many relations

    /**
     * @return BelongsToMany
     */
    public function devices() : BelongsToMany
    {
        return $this->belongsToMany(
            Device::class,
            'device_vybe',
            'vybe_id',
            'device_id'
        );
    }

    /**
     * @return BelongsToMany
     */
    public function userFavorites() : BelongsToMany
    {
        return $this->belongsToMany(
            Vybe::class,
            'favorite_vybes',
            'vybe_id',
            'user_id'
        );
    }

    //--------------------------------------------------------------------------
    // Has many through relations

    /**
     * @return HasManyThrough
     */
    public function timeslots() : HasManyThrough
    {
        return $this->hasManyThrough(
            Timeslot::class,
            OrderItem::class,
            'vybe_id',
            'id',
            'id',
            'id'
        );
    }

    //--------------------------------------------------------------------------
    // Has many deep relations

    /**
     * @return HasManyDeep
     */
    public function platforms() : HasManyDeep
    {
        return $this->hasManyDeep(
            Platform::class, [
                AppearanceCase::class,
                'appearance_case_platform'
            ]
        );
    }

    //--------------------------------------------------------------------------
    // Has many relations

    /**
     * @return HasMany
     */
    public function appearanceCases() : HasMany
    {
        return $this->hasMany(
            AppearanceCase::class,
            'vybe_id',
            'id'
        );
    }

    /**
     * @return HasMany
     */
    public function orderItems() : HasMany
    {
        return $this->hasMany(
            OrderItem::class,
            'vybe_id',
            'id'
        );
    }

    /**
     * @return HasMany
     */
    public function ratingVotes() : HasMany
    {
        return $this->hasMany(
            VybeRatingVote::class,
            'vybe_id',
            'id'
        );
    }

    /**
     * @return HasMany
     */
    public function schedules() : HasMany
    {
        return $this->hasMany(
            Schedule::class,
            'vybe_id',
            'id'
        )->orderBy(
            'datetime_from'
        );
    }

    /**
     * @return MongoHasMany
     */
    public function versions() : MongoHasMany
    {
        return $this->hasMany(
            VybeVersion::class,
            'vybe_id',
            'id'
        );
    }

    /**
     * @return HasMany
     */
    public function publishRequests() : HasMany
    {
        return $this->hasMany(VybePublishRequest::class);
    }

    /**
     * @return HasMany
     */
    public function changeRequests() : HasMany
    {
        return $this->hasMany(VybeChangeRequest::class);
    }

    /**
     * @return HasMany
     */
    public function unpublishRequests() : HasMany
    {
        return $this->hasMany(VybeUnpublishRequest::class);
    }

    /**
     * @return HasMany
     */
    public function unsuspendRequests() : HasMany
    {
        return $this->hasMany(VybeUnsuspendRequest::class);
    }

    /**
     * @return HasMany
     */
    public function deletionRequests() : HasMany
    {
        return $this->hasMany(VybeDeletionRequest::class);
    }

    //--------------------------------------------------------------------------
    // Has one relations

    /**
     * @return HasOne
     */
    public function support() : HasOne
    {
        return $this->hasOne(VybeSupport::class);
    }

    /**
     * @return HasOne
     */
    public function publishRequest() : HasOne
    {
        return $this->hasOne(VybePublishRequest::class)->latest();
    }

    /**
     * @return HasOne
     */
    public function changeRequest() : HasOne
    {
        return $this->hasOne(VybeChangeRequest::class)->latest();
    }

    /**
     * @return HasOne
     */
    public function unpublishRequest() : HasOne
    {
        return $this->hasOne(VybeUnpublishRequest::class)->latest();
    }

    /**
     * @return HasOne
     */
    public function unsuspendRequest() : HasOne
    {
        return $this->hasOne(VybeUnsuspendRequest::class)->latest();
    }

    /**
     * @return HasOne
     */
    public function deletionRequest() : HasOne
    {
        return $this->hasOne(VybeDeletionRequest::class)->latest();
    }

    //--------------------------------------------------------------------------
    // Magic accessors

    /**
     * @return string|null
     */
    public function getDecryptedAccessPassword() : ?string
    {
        if ($this->access_password) {
            return Crypt::decrypt($this->access_password);
        }

        return null;
    }

    /**
     * @return Category|null
     */
    public function getCategoryAttribute() : ?Category
    {
        /**
         * Checking activity existence
         */
        if (!$this->activity) {
            if ($this->support) {
                return $this->support->category;
            } else {
                return null;
            }
        } elseif ($this->activity
            ->category
            ->parent
        ) {
            return $this->activity
                ->category
                ->parent;
        }

        return $this->activity
            ->category;
    }

    /**
     * @return Category|null
     */
    public function getSubcategoryAttribute() : ?Category
    {
        /**
         * Checking activity existence
         */
        if (!$this->activity) {
            return null;

            /**
             * Checking activity parent category existence
             */
        } elseif ($this->activity
            ->category
            ->parent
        ) {
            return $this->activity
                ->category;
        }

        return null;
    }

    /**
     * @return string|null
     */
    public function getCategorySuggestionAttribute() : ?string
    {
        return $this->support ?
            $this->support->category_suggestion :
            null;
    }

    /**
     * @return string|null
     */
    public function getSubcategorySuggestionAttribute() : ?string
    {
        return $this->support ?
            $this->support->subcategory_suggestion :
            null;
    }

    /**
     * @return string|null
     */
    public function getActivitySuggestionAttribute() : ?string
    {
        return $this->support ?
            $this->support->activity_suggestion :
            null;
    }

    /**
     * @return string|null
     */
    public function getDeviceSuggestionAttribute() : ?string
    {
        return $this->support ?
            $this->support->device_suggestion :
            null;
    }

    /**
     * @return string
     */
    public function getFullIdAttribute() : string
    {
        return 'V' . $this->id;
    }

    /**
     * @return string
     */
    public function getFullVersionAttribute() : string
    {
        return $this->full_id . '-' . $this->version;
    }

    //--------------------------------------------------------------------------
    // Lists accessors

    /**
     * @return VybeTypeListItem|null
     */
    public function getType() : ?VybeTypeListItem
    {
        return VybeTypeList::getItem(
            $this->type_id
        );
    }

    /**
     * @return VybePeriodListItem|null
     */
    public function getPeriod() : ?VybePeriodListItem
    {
        return VybePeriodList::getItem(
            $this->period_id
        );
    }

    /**
     * @return VybeAccessListItem|null
     */
    public function getAccess() : ?VybeAccessListItem
    {
        return VybeAccessList::getItem(
            $this->access_id
        );
    }

    /**
     * @return VybeShowcaseListItem|null
     */
    public function getShowcase() : ?VybeShowcaseListItem
    {
        return VybeShowcaseList::getItem(
            $this->showcase_id
        );
    }

    /**
     * @return VybeOrderAcceptListItem|null
     */
    public function getOrderAccept() : ?VybeOrderAcceptListItem
    {
        return VybeOrderAcceptList::getItem(
            $this->order_accept_id
        );
    }

    /**
     * @return VybeAgeLimitListItem|null
     */
    public function getAgeLimit() : ?VybeAgeLimitListItem
    {
        return VybeAgeLimitList::getItem(
            $this->age_limit_id
        );
    }

    /**
     * @return VybeStatusListItem|null
     */
    public function getStatus() : ?VybeStatusListItem
    {
        return VybeStatusList::getItem(
            $this->status_id
        );
    }

    /**
     * @return VybeStepListItem|null
     */
    public function getStep() : ?VybeStepListItem
    {
        return VybeStepList::getItem(
            $this->step_id
        );
    }
}
