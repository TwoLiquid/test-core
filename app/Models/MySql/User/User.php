<?php

namespace App\Models\MySql\User;

use App\Lists\Account\Status\AccountStatusList;
use App\Lists\Account\Status\AccountStatusListItem;
use App\Lists\Currency\CurrencyList;
use App\Lists\Currency\CurrencyListItem;
use App\Lists\Gender\GenderList;
use App\Lists\Gender\GenderListItem;
use App\Lists\Language\LanguageList;
use App\Lists\Language\LanguageListItem;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\User\Balance\Type\UserBalanceTypeList;
use App\Lists\User\IdVerification\Status\UserIdVerificationStatusList;
use App\Lists\User\IdVerification\Status\UserIdVerificationStatusListItem;
use App\Lists\User\Label\UserLabelList;
use App\Lists\User\Label\UserLabelListItem;
use App\Lists\User\State\Status\UserStateStatusList;
use App\Lists\User\State\Status\UserStateStatusListItem;
use App\Lists\User\Theme\UserThemeList;
use App\Lists\User\Theme\UserThemeListItem;
use App\Lists\Vybe\Status\VybeStatusList;
use App\Models\MongoDb\User\Request\Deactivation\UserDeactivationRequest;
use App\Models\MongoDb\User\Request\Deletion\UserDeletionRequest;
use App\Models\MongoDb\User\Request\Profile\UserProfileRequest;
use App\Models\MongoDb\User\Request\Unsuspend\UserUnsuspendRequest;
use App\Models\MySql\Activity\Activity;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\Billing;
use App\Models\MySql\CartItem;
use App\Models\MySql\Language;
use App\Models\MySql\NotificationSetting;
use App\Models\MySql\Order\Order;
use App\Models\MySql\Order\OrderItem;
use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\PersonalityTrait\PersonalityTrait;
use App\Models\MySql\Place\CityPlace;
use App\Models\MySql\Timeslot;
use App\Models\MySql\Timezone\Timezone;
use App\Models\MySql\Vybe\Vybe;
use Closure;
use Database\Factories\MySql\User\UserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use MongoDB\Laravel\Helpers\EloquentBuilder;
use MongoDB\Laravel\Relations\HasMany as MongoHasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use MongoDB\Laravel\Eloquent\HybridRelations;
use Eloquent;

/**
 * App\Models\MySql\User\User
 *
 * @property int $id
 * @property int $auth_id
 * @property int|null $gender_id
 * @property int|null $currency_id
 * @property int|null $language_id
 * @property int|null $label_id
 * @property int|null $state_status_id
 * @property int|null $account_status_id
 * @property int|null $verification_status_id
 * @property int|null $referred_user_id
 * @property int|null $suspend_admin_id
 * @property int|null $timezone_id
 * @property int $theme_id
 * @property string|null $current_city_place_id
 * @property string $username
 * @property string $email
 * @property string|null $email_verify_token
 * @property Carbon|null $email_verified_at
 * @property Carbon|null $last_email_changed_at
 * @property Carbon|null $birth_date
 * @property string|null $description
 * @property string|null $invitation_code
 * @property string|null $suspend_reason
 * @property bool $streamer_badge
 * @property bool $streamer_milestone
 * @property bool $verified_celebrity
 * @property bool $verified_partner
 * @property bool $verify_blocked
 * @property bool $verification_suspended
 * @property bool $hide_gender
 * @property bool $hide_age
 * @property bool $hide_reviews
 * @property bool $hide_location
 * @property bool $receive_news
 * @property bool $enable_fast_order
 * @property bool $top_vybers
 * @property bool $vpn_used
 * @property int|null $avatar_id
 * @property int|null $background_id
 * @property int|null $voice_sample_id
 * @property array|null $images_ids
 * @property array|null $videos_ids
 * @property string|null $password_reset_token
 * @property int $login_attempts_left
 * @property Carbon|null $next_login_attempt_at
 * @property int $email_attempts_left
 * @property Carbon|null $next_email_attempt_at
 * @property int $password_attempts_left
 * @property Carbon|null $next_password_attempt_at
 * @property Carbon|null $signed_up_at
 * @property Carbon|null $temporary_deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 * @property-read Collection<int, UserBalance> $balances
 * @property-read int|null $balances_count
 * @property-read Billing|null $billing
 * @property-read Collection<int, User> $blockList
 * @property-read int|null $block_list_count
 * @property-read Collection<int, CartItem> $cartItems
 * @property-read int|null $cart_items_count
 * @property-read CityPlace|null $currentCityPlace
 * @property-read UserDeactivationRequest|null $deactivationRequest
 * @property-read Collection<int, UserDeactivationRequest> $deactivationRequests
 * @property-read int|null $deactivation_requests_count
 * @property-read UserDeletionRequest|null $deletionRequest
 * @property-read Collection<int, UserDeletionRequest> $deletionRequests
 * @property-read int|null $deletion_requests_count
 * @property-read Collection<int, Activity> $favoriteActivities
 * @property-read int|null $favorite_activities_count
 * @property-read Collection<int, Vybe> $favoriteVybes
 * @property-read int|null $favorite_vybes_count
 * @property-read Collection<int, Language> $languages
 * @property-read int|null $languages_count
 * @property-read NotificationSetting|null $notificationSettings
 * @property-read DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection<int, Order> $orders
 * @property-read int|null $orders_count
 * @property-read Collection<int, PaymentMethod> $payoutMethods
 * @property-read int|null $payout_methods_count
 * @property-read Collection<int, PersonalityTrait> $personalityTraits
 * @property-read int|null $personality_traits_count
 * @property-read UserProfileRequest|null $profileRequest
 * @property-read Collection<int, UserProfileRequest> $profileRequests
 * @property-read int|null $profile_requests_count
 * @property-read User|null $referredUser
 * @property-read Collection<int, OrderItem> $sales
 * @property-read int|null $sales_count
 * @property-read Collection<int, User> $subscribers
 * @property-read int|null $subscribers_count
 * @property-read Collection<int, User> $subscriptions
 * @property-read int|null $subscriptions_count
 * @property-read Admin|null $suspendAdmin
 * @property-read Collection<int, Timeslot> $timeslots
 * @property-read int|null $timeslots_count
 * @property-read Timezone|null $timezone
 * @property-read UserUnsuspendRequest|null $unsuspendRequest
 * @property-read Collection<int, UserUnsuspendRequest> $unsuspendRequests
 * @property-read int|null $unsuspend_requests_count
 * @property-read Collection<int, User> $visitedUsers
 * @property-read int|null $visited_users_count
 * @property-read Collection<int, Vybe> $vybes
 * @property-read int|null $vybes_count
 * @property-read Collection<int, User> $youBlockedList
 * @property-read int|null $you_blocked_list_count
 * @property-read mixed $pivot
 * @method static EloquentBuilder|User addHybridHas(Relation $relation, string $operator = '>=', string $count = 1, string $boolean = 'and', ?Closure $callback = null)
 * @method static UserFactory factory($count = null, $state = [])
 * @method static EloquentBuilder|User newModelQuery()
 * @method static EloquentBuilder|User newQuery()
 * @method static Builder|User onlyTrashed()
 * @method static EloquentBuilder|User query()
 * @method static EloquentBuilder|User whereAccountStatusId($value)
 * @method static EloquentBuilder|User whereAuthId($value)
 * @method static EloquentBuilder|User whereAvatarId($value)
 * @method static EloquentBuilder|User whereBackgroundId($value)
 * @method static EloquentBuilder|User whereBirthDate($value)
 * @method static EloquentBuilder|User whereCreatedAt($value)
 * @method static EloquentBuilder|User whereCurrencyId($value)
 * @method static EloquentBuilder|User whereCurrentCityPlaceId($value)
 * @method static EloquentBuilder|User whereDeletedAt($value)
 * @method static EloquentBuilder|User whereDescription($value)
 * @method static EloquentBuilder|User whereEmail($value)
 * @method static EloquentBuilder|User whereEmailAttemptsLeft($value)
 * @method static EloquentBuilder|User whereEmailVerifiedAt($value)
 * @method static EloquentBuilder|User whereEmailVerifyToken($value)
 * @method static EloquentBuilder|User whereEnableFastOrder($value)
 * @method static EloquentBuilder|User whereGenderId($value)
 * @method static EloquentBuilder|User whereHideAge($value)
 * @method static EloquentBuilder|User whereHideGender($value)
 * @method static EloquentBuilder|User whereHideLocation($value)
 * @method static EloquentBuilder|User whereHideReviews($value)
 * @method static EloquentBuilder|User whereId($value)
 * @method static EloquentBuilder|User whereImagesIds($value)
 * @method static EloquentBuilder|User whereInvitationCode($value)
 * @method static EloquentBuilder|User whereLabelId($value)
 * @method static EloquentBuilder|User whereLanguageId($value)
 * @method static EloquentBuilder|User whereLastEmailChangedAt($value)
 * @method static EloquentBuilder|User whereLoginAttemptsLeft($value)
 * @method static EloquentBuilder|User whereNextEmailAttemptAt($value)
 * @method static EloquentBuilder|User whereNextLoginAttemptAt($value)
 * @method static EloquentBuilder|User whereNextPasswordAttemptAt($value)
 * @method static EloquentBuilder|User wherePasswordAttemptsLeft($value)
 * @method static EloquentBuilder|User wherePasswordResetToken($value)
 * @method static EloquentBuilder|User whereReceiveNews($value)
 * @method static EloquentBuilder|User whereReferredUserId($value)
 * @method static EloquentBuilder|User whereSignedUpAt($value)
 * @method static EloquentBuilder|User whereStateStatusId($value)
 * @method static EloquentBuilder|User whereStreamerBadge($value)
 * @method static EloquentBuilder|User whereStreamerMilestone($value)
 * @method static EloquentBuilder|User whereSuspendAdminId($value)
 * @method static EloquentBuilder|User whereSuspendReason($value)
 * @method static EloquentBuilder|User whereTemporaryDeletedAt($value)
 * @method static EloquentBuilder|User whereThemeId($value)
 * @method static EloquentBuilder|User whereTimezoneId($value)
 * @method static EloquentBuilder|User whereTopVybers($value)
 * @method static EloquentBuilder|User whereUpdatedAt($value)
 * @method static EloquentBuilder|User whereUsername($value)
 * @method static EloquentBuilder|User whereVerificationStatusId($value)
 * @method static EloquentBuilder|User whereVerificationSuspended($value)
 * @method static EloquentBuilder|User whereVerifiedCelebrity($value)
 * @method static EloquentBuilder|User whereVerifiedPartner($value)
 * @method static EloquentBuilder|User whereVerifyBlocked($value)
 * @method static EloquentBuilder|User whereVideosIds($value)
 * @method static EloquentBuilder|User whereVoiceSampleId($value)
 * @method static EloquentBuilder|User whereVpnUsed($value)
 * @method static Builder|User withTrashed()
 * @method static Builder|User withoutTrashed()
 * @mixin Eloquent
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HybridRelations;

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
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'auth_id', 'gender_id', 'language_id', 'currency_id', 'label_id',
        'state_status_id', 'account_status_id', 'verification_status_id', 'referred_user_id', 'suspend_admin_id',
        'timezone_id', 'theme_id', 'current_city_place_id', 'username', 'email', 'email_verify_token',
        'email_verified_at', 'last_email_changed_at', 'hide_gender', 'hide_age', 'hide_reviews', 'hide_location',
        'birth_date', 'description', 'suspend_reason', 'streamer_badge', 'streamer_milestone', 'verified_celebrity',
        'verified_partner', 'verify_blocked', 'verification_suspended', 'enable_fast_order', 'top_vybers',
        'vpn_used', 'avatar_id', 'background_id', 'voice_sample_id', 'images_ids', 'videos_ids',
        'password_reset_token', 'login_attempts_left', 'next_login_attempt_at', 'email_attempts_left',
        'next_email_attempt_at', 'password_attempts_left', 'next_password_attempt_at',
        'signed_up_at', 'temporary_deleted_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'email_verify_token',
        'password_reset_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'streamer_badge'           => 'boolean',
        'streamer_milestone'       => 'boolean',
        'verified_celebrity'       => 'boolean',
        'verified_partner'         => 'boolean',
        'verify_blocked'           => 'boolean',
        'verification_suspended'   => 'boolean',
        'hide_gender'              => 'boolean',
        'hide_age'                 => 'boolean',
        'hide_reviews'             => 'boolean',
        'hide_location'            => 'boolean',
        'receive_news'             => 'boolean',
        'enable_fast_order'        => 'boolean',
        'top_vybers'               => 'boolean',
        'vpn_used'                 => 'boolean',
        'images_ids'               => 'array',
        'videos_ids'               => 'array',
        'birth_date'               => 'datetime',
        'email_verified_at'        => 'datetime',
        'last_email_changed_at'    => 'datetime',
        'next_login_attempt_at'    => 'datetime',
        'next_email_attempt_at'    => 'datetime',
        'next_password_attempt_at' => 'datetime',
        'signed_up_at'             => 'datetime',
        'temporary_deleted_at'     => 'datetime'
    ];

    //--------------------------------------------------------------------------
    // Belongs to relations

    /**
     * @return BelongsTo
     */
    public function timezone() : BelongsTo
    {
        return $this->belongsTo(Timezone::class);
    }

    /**
     * @return BelongsTo
     */
    public function currentCityPlace() : BelongsTo
    {
        return $this->belongsTo(
            CityPlace::class,
            'current_city_place_id',
            'place_id'
        );
    }

    /**
     * @return BelongsTo
     */
    public function referredUser() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function suspendAdmin() : BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    //--------------------------------------------------------------------------
    // Client belongs to many relations

    /**
     * @return BelongsToMany
     */
    public function favoriteActivities() : BelongsToMany
    {
        return $this->belongsToMany(
            Activity::class,
            'favorite_activities',
            'user_id',
            'activity_id'
        );
    }

    /**
     * @return BelongsToMany
     */
    public function blockList() : BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'user_block_list',
            'user_id',
            'blocked_user_id'
        )->withPivot([
            'blocked_at'
        ]);
    }

    /**
     * @return BelongsToMany
     */
    public function youBlockedList() : BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'user_block_list',
            'blocked_user_id',
            'user_id'
        )->withPivot([
            'blocked_at'
        ]);
    }

    /**
     * @return BelongsToMany
     */
    public function favoriteVybes() : BelongsToMany
    {
        return $this->belongsToMany(
            Vybe::class,
            'favorite_vybes',
            'user_id',
            'vybe_id'
        );
    }

    /**
     * @return BelongsToMany
     */
    public function payoutMethods() : BelongsToMany
    {
        return $this->belongsToMany(
            PaymentMethod::class,
            'payment_method_user',
            'user_id',
            'method_id'
        );
    }

    /**
     * @return BelongsToMany
     */
    public function subscribers() : belongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'subscriptions',
            'subscription_id',
            'user_id'
        )->withPivot(
            'added_at'
        );
    }

    /**
     * @return BelongsToMany
     */
    public function subscriptions() : belongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'subscriptions',
            'user_id',
            'subscription_id'
        )->withPivot(
            'added_at'
        );
    }

    /**
     * @return BelongsToMany
     */
    public function timeslots() : belongsToMany
    {
        return $this->belongsToMany(Timeslot::class);
    }

    /**
     * @return BelongsToMany
     */
    public function visitedUsers() : BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'user_recent_visits',
            'user_id',
            'visited_user_id'
        );
    }

    //--------------------------------------------------------------------------
    // Client has many relations

    /**
     * @return HasMany
     */
    public function balances() : HasMany
    {
        return $this->hasMany(
            UserBalance::class,
            'user_id',
            'id'
        );
    }

    /**
     * @return HasMany
     */
    public function cartItems() : HasMany
    {
        return $this->hasMany(
            CartItem::class,
            'user_id',
            'id'
        );
    }

    /**
     * @return HasMany
     */
    public function languages() : HasMany
    {
        return $this->hasMany(
            Language::class,
            'user_id',
            'id'
        );
    }

    /**
     * @return HasMany
     */
    public function orders() : HasMany
    {
        return $this->hasMany(
            Order::class,
            'buyer_id',
            'id'
        );
    }

    /**
     * @return HasMany
     */
    public function personalityTraits() : HasMany
    {
        return $this->hasMany(
            PersonalityTrait::class,
            'user_id',
            'id'
        );
    }

    /**
     * @return HasMany
     */
    public function vybes() : HasMany
    {
        return $this->hasMany(
            Vybe::class
        );
    }

    /**
     * @return HasMany
     */
    public function sales() : HasMany
    {
        return $this->hasMany(
            OrderItem::class,
            'seller_id',
            'id'
        );
    }

    //--------------------------------------------------------------------------
    // Client has many through relations

    /**
     * @return HasManyThrough
     */
    public function activities() : HasManyThrough
    {
        return $this->hasManyThrough(
            Activity::class,
            Vybe::class,
            'user_id',
            'id',
            'id',
            'activity_id'
        )->whereIn('vybes.status_id', [
            VybeStatusList::getPublishedItem()->id,
            VybeStatusList::getPausedItem()->id
        ]);
    }

    /**
     * @return MongoHasMany
     */
    public function deactivationRequests() : MongoHasMany
    {
        return $this->hasMany(UserDeactivationRequest::class);
    }

    /**
     * @return MongoHasMany
     */
    public function deletionRequests() : MongoHasMany
    {
        return $this->hasMany(UserDeletionRequest::class);
    }

    /**
     * @return MongoHasMany
     */
    public function profileRequests() : MongoHasMany
    {
        return $this->hasMany(UserProfileRequest::class);
    }

    /**
     * @return MongoHasMany
     */
    public function unsuspendRequests() : MongoHasMany
    {
        return $this->hasMany(UserUnsuspendRequest::class);
    }

    //--------------------------------------------------------------------------
    // Client has one relations

    /**
     * @return HasOne
     */
    public function billing() : HasOne
    {
        return $this->hasOne(Billing::class);
    }

    /**
     * @return HasOne
     */
    public function notificationSettings() : HasOne
    {
        return $this->hasOne(NotificationSetting::class);
    }

    /**
     * @return HasOne
     */
    public function profileRequest() : HasOne
    {
        return $this->hasOne(UserProfileRequest::class)->latest();
    }

    /**
     * @return HasOne
     */
    public function deactivationRequest() : HasOne
    {
        return $this->hasOne(UserDeactivationRequest::class)->latest();
    }

    /**
     * @return HasOne
     */
    public function unsuspendRequest() : HasOne
    {
        return $this->hasOne(UserUnsuspendRequest::class)->latest();
    }

    /**
     * @return HasOne
     */
    public function deletionRequest() : HasOne
    {
        return $this->hasOne(UserDeletionRequest::class)->latest();
    }

    //--------------------------------------------------------------------------
    // Methods

    /**
     * @return UserDeactivationRequest|null
     */
    public function getPendingDeactivationRequest() : ?UserDeactivationRequest
    {
        return $this->deactivationRequests
            ->where('request_status_id', '=', RequestStatusList::getPendingItem()->id)
            ->first();
    }

    /**
     * @return UserDeletionRequest|null
     */
    public function getPendingDeletionRequest() : ?UserDeletionRequest
    {
        return $this->deletionRequests
            ->where('request_status_id', '=', RequestStatusList::getPendingItem()->id)
            ->first();
    }

    /**
     * @return UserUnsuspendRequest|null
     */
    public function getPendingUnsuspendRequest() : ?UserUnsuspendRequest
    {
        return $this->unsuspendRequests
            ->where('request_status_id', '=', RequestStatusList::getPendingItem()->id)
            ->first();
    }

    /**
     * @return UserBalance|null
     */
    public function getBuyerBalance() : ?UserBalance
    {
        return $this->balances
            ->where('type_id', '=', UserBalanceTypeList::getBuyer()->id)
            ->first();
    }

    /**
     * @return UserBalance|null
     */
    public function getSellerBalance() : ?UserBalance
    {
        return $this->balances
            ->where('type_id', '=', UserBalanceTypeList::getSeller()->id)
            ->first();
    }

    /**
     * @return UserBalance|null
     */
    public function getAffiliateBalance() : ?UserBalance
    {
        return $this->balances
            ->where('type_id', '=', UserBalanceTypeList::getAffiliate()->id)
            ->first();
    }

    //--------------------------------------------------------------------------
    // Lists accessors

    /**
     * @return GenderListItem|null
     */
    public function getGender() : ?GenderListItem
    {
        return GenderList::getItem(
            $this->gender_id
        );
    }

    /**
     * @return LanguageListItem|null
     */
    public function getLanguage() : ?LanguageListItem
    {
        return LanguageList::getItem(
            $this->language_id
        );
    }

    /**
     * @return CurrencyListItem|null
     */
    public function getCurrency() : ?CurrencyListItem
    {
        return CurrencyList::getItem(
            $this->currency_id
        );
    }

    /**
     * @return UserLabelListItem|null
     */
    public function getLabel() : ?UserLabelListItem
    {
        return UserLabelList::getItem(
            $this->label_id
        );
    }

    /**
     * @return UserStateStatusListItem|null
     */
    public function getStateStatus() : ?UserStateStatusListItem
    {
        return UserStateStatusList::getItem(
            $this->state_status_id
        );
    }

    /**
     * @return AccountStatusListItem|null
     */
    public function getAccountStatus() : ?AccountStatusListItem
    {
        return AccountStatusList::getItem(
            $this->account_status_id
        );
    }

    /**
     * @return UserThemeListItem|null
     */
    public function getTheme() : ?UserThemeListItem
    {
        return UserThemeList::getItem(
            $this->theme_id
        );
    }

    /**
     * @return UserIdVerificationStatusListItem|null
     */
    public function getIdVerificationStatus() : ?UserIdVerificationStatusListItem
    {
        return UserIdVerificationStatusList::getItem(
            $this->verification_status_id
        );
    }
}
