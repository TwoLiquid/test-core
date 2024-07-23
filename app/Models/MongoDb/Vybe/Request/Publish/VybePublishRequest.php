<?php

namespace App\Models\MongoDb\Vybe\Request\Publish;

use App\Lists\Language\LanguageList;
use App\Lists\Language\LanguageListItem;
use App\Lists\Request\Field\Status\RequestFieldStatusList;
use App\Lists\Request\Field\Status\RequestFieldStatusListItem;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Lists\ToastMessage\Type\ToastMessageTypeList;
use App\Lists\ToastMessage\Type\ToastMessageTypeListItem;
use App\Lists\Vybe\Access\VybeAccessList;
use App\Lists\Vybe\Access\VybeAccessListItem;
use App\Lists\Vybe\AgeLimit\VybeAgeLimitList;
use App\Lists\Vybe\AgeLimit\VybeAgeLimitListItem;
use App\Lists\Vybe\OrderAccept\VybeOrderAcceptList;
use App\Lists\Vybe\OrderAccept\VybeOrderAcceptListItem;
use App\Lists\Vybe\Period\VybePeriodList;
use App\Lists\Vybe\Period\VybePeriodListItem;
use App\Lists\Vybe\Showcase\VybeShowcaseList;
use App\Lists\Vybe\Showcase\VybeShowcaseListItem;
use App\Lists\Vybe\Status\VybeStatusList;
use App\Lists\Vybe\Status\VybeStatusListItem;
use App\Lists\Vybe\Type\VybeTypeList;
use App\Lists\Vybe\Type\VybeTypeListItem;
use App\Models\MongoDb\Suggestion\CsauSuggestion;
use App\Models\MongoDb\Suggestion\DeviceSuggestion;
use App\Models\MySql\Activity\Activity;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\Category;
use App\Models\MySql\Vybe\Vybe;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use MongoDB\Laravel\Eloquent\Model;

/**
 * App\Models\MongoDb\Vybe\Request\Publish\VybePublishRequest
 *
 * @property int $_id
 * @property string $vybe_id
 * @property string $title
 * @property string $previous_title
 * @property int $title_status_id
 * @property int $category_id
 * @property int $previous_category_id
 * @property string $category_suggestion
 * @property int $category_status_id
 * @property int $subcategory_id
 * @property int $previous_subcategory_id
 * @property string $subcategory_suggestion
 * @property int $subcategory_status_id
 * @property int $activity_id
 * @property int $previous_activity_id
 * @property string $activity_suggestion
 * @property int $activity_status_id
 * @property array $devices_ids
 * @property array $previous_devices_ids
 * @property string $device_suggestion
 * @property int $devices_status_id
 * @property int $period_id
 * @property int $previous_period_id
 * @property int $period_status_id
 * @property int $user_count
 * @property int $previous_user_count
 * @property int $user_count_status_id
 * @property int $type_id
 * @property int $previous_type_id
 * @property int $schedules_status_id
 * @property int $order_advance
 * @property int $previous_order_advance
 * @property int $order_advance_status_id
 * @property array $images_ids
 * @property array $previous_images_ids
 * @property array $videos_ids
 * @property array $previous_videos_ids
 * @property string $access_password
 * @property int $access_id
 * @property int $previous_access_id
 * @property int $access_status_id
 * @property int $showcase_id
 * @property int $previous_showcase_id
 * @property int $showcase_status_id
 * @property int $order_accept_id
 * @property int $previous_order_accept_id
 * @property int $order_accept_status_id
 * @property int $age_limit_id
 * @property int $status_id
 * @property int $previous_status_id
 * @property int $status_status_id
 * @property int $toast_message_type_id
 * @property int $toast_message_text
 * @property int $request_status_id
 * @property bool $shown
 * @property int $admin_id
 * @property int $language_id
 * @property Carbon $created_at
 * @property-read Vybe $vybe
 * @property-read Category $category
 * @property-read Category $previousCategory
 * @property-read Category $subcategory
 * @property-read Category $previousSubcategory
 * @property-read Activity $activity
 * @property-read Activity $previousActivity
 * @property-read Collection $appearanceCases
 * @property-read Collection $schedules
 * @property-read Collection $csauSuggestions
 * @property-read DeviceSuggestion $deviceSuggestion
 * @property-read Admin $admin
 * @method static Builder|VybePublishRequest find(string $id)
 * @method static Builder|VybePublishRequest query()
 */
class VybePublishRequest extends Model
{
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
    protected $collection = 'vybe_publish_requests';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vybe_id', 'title', 'previous_title', 'title_status_id', 'category_id', 'previous_category_id',
        'category_suggestion', 'category_status_id', 'subcategory_id', 'previous_subcategory_id',
        'subcategory_suggestion', 'subcategory_status_id', 'activity_id', 'previous_activity_id',
        'activity_suggestion', 'activity_status_id', 'devices_ids', 'previous_devices_ids', 'device_suggestion',
        'devices_status_id', 'period_id', 'previous_period_id', 'period_status_id', 'user_count',
        'previous_user_count', 'user_count_status_id', 'type_id', 'previous_type_id', 'schedules_status_id',
        'order_advance', 'previous_order_advance', 'order_advance_status_id', 'images_ids', 'previous_images_ids',
        'videos_ids', 'previous_videos_ids', 'access_password', 'access_id', 'previous_access_id',
        'access_status_id', 'showcase_id', 'previous_showcase_id', 'showcase_status_id', 'order_accept_id',
        'previous_order_accept_id', 'order_accept_status_id', 'age_limit_id', 'status_id', 'previous_status_id',
        'status_status_id', 'toast_message_type_id', 'toast_message_text', 'request_status_id',
        'shown', 'admin_id', 'language_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'devices_ids' => 'array',
        'images_ids'  => 'array',
        'videos_ids'  => 'array',
        'shown'       => 'boolean'
    ];

    //--------------------------------------------------------------------------
    // Belongs to relations

    /**
     * @return BelongsTo
     */
    public function admin() : BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

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
    public function category() : BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return BelongsTo
     */
    public function previousCategory() : BelongsTo
    {
        return $this->belongsTo(
            Category::class,
            'previous_category_id',
            'id'
        );
    }

    /**
     * @return BelongsTo
     */
    public function subcategory() : BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return BelongsTo
     */
    public function previousSubcategory() : BelongsTo
    {
        return $this->belongsTo(
            Category::class,
            'previous_subcategory_id',
            'id'
        );
    }

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
    public function previousActivity() : BelongsTo
    {
        return $this->belongsTo(
            Activity::class,
            'previous_activity_id',
            'id'
        );
    }

    /**
     * @return BelongsTo
     */
    public function deviceSuggestion() : BelongsTo
    {
        return $this->belongsTo(DeviceSuggestion::class);
    }

    //--------------------------------------------------------------------------
    // Has many relations

    /**
     * @return HasMany
     */
    public function appearanceCases() : HasMany
    {
        return $this->hasMany(VybePublishRequestAppearanceCase::class);
    }

    /**
     * @return HasMany
     */
    public function schedules() : HasMany
    {
        return $this->hasMany(VybePublishRequestSchedule::class);
    }

    //--------------------------------------------------------------------------
    // Has many relations

    /**
     * @return HasMany
     */
    public function csauSuggestions() : HasMany
    {
        return $this->hasMany(
            CsauSuggestion::class,
            'vybe_publish_request_id',
            '_id'
        );
    }

    //--------------------------------------------------------------------------
    // Lists accessors

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
     * @return VybePeriodListItem|null
     */
    public function getPreviousPeriod() : ?VybePeriodListItem
    {
        return VybePeriodList::getItem(
            $this->previous_period_id
        );
    }

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
     * @return VybeTypeListItem|null
     */
    public function getPreviousType() : ?VybeTypeListItem
    {
        return VybeTypeList::getItem(
            $this->previous_type_id
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
     * @return VybeAccessListItem|null
     */
    public function getPreviousAccess() : ?VybeAccessListItem
    {
        return VybeAccessList::getItem(
            $this->previous_access_id
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
     * @return VybeShowcaseListItem|null
     */
    public function getPreviousShowcase() : ?VybeShowcaseListItem
    {
        return VybeShowcaseList::getItem(
            $this->previous_showcase_id
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
     * @return VybeOrderAcceptListItem|null
     */
    public function getPreviousOrderAccept() : ?VybeOrderAcceptListItem
    {
        return VybeOrderAcceptList::getItem(
            $this->previous_order_accept_id
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
     * @return VybeStatusListItem|null
     */
    public function getPreviousStatus() : ?VybeStatusListItem
    {
        return VybeStatusList::getItem(
            $this->previous_status_id
        );
    }

    /**
     * @return ToastMessageTypeListItem|null
     */
    public function getToastMessageType() : ?ToastMessageTypeListItem
    {
        return ToastMessageTypeList::getItem(
            $this->toast_message_type_id
        );
    }

    //--------------------------------------------------------------------------
    // Request field statuses

    /**
     * @return RequestFieldStatusListItem|null
     */
    public function getTitleStatus() : ?RequestFieldStatusListItem
    {
        return RequestFieldStatusList::getItem(
            $this->title_status_id
        );
    }

    /**
     * @return RequestFieldStatusListItem|null
     */
    public function getCategoryStatus() : ?RequestFieldStatusListItem
    {
        return RequestFieldStatusList::getItem(
            $this->category_status_id
        );
    }

    /**
     * @return RequestFieldStatusListItem|null
     */
    public function getSubcategoryStatus() : ?RequestFieldStatusListItem
    {
        return RequestFieldStatusList::getItem(
            $this->subcategory_status_id
        );
    }

    /**
     * @return RequestFieldStatusListItem|null
     */
    public function getActivityStatus() : ?RequestFieldStatusListItem
    {
        return RequestFieldStatusList::getItem(
            $this->activity_status_id
        );
    }

    /**
     * @return RequestFieldStatusListItem|null
     */
    public function getDevicesStatus() : ?RequestFieldStatusListItem
    {
        return RequestFieldStatusList::getItem(
            $this->devices_status_id
        );
    }

    /**
     * @return RequestFieldStatusListItem|null
     */
    public function getPeriodStatus() : ?RequestFieldStatusListItem
    {
        return RequestFieldStatusList::getItem(
            $this->period_status_id
        );
    }

    /**
     * @return RequestFieldStatusListItem|null
     */
    public function getUserCountStatus() : ?RequestFieldStatusListItem
    {
        return RequestFieldStatusList::getItem(
            $this->user_count_status_id
        );
    }

    /**
     * @return RequestFieldStatusListItem|null
     */
    public function getSchedulesStatus() : ?RequestFieldStatusListItem
    {
        return RequestFieldStatusList::getItem(
            $this->schedules_status_id
        );
    }

    /**
     * @return RequestFieldStatusListItem|null
     */
    public function getOrderAdvanceStatus() : ?RequestFieldStatusListItem
    {
        return RequestFieldStatusList::getItem(
            $this->order_advance_status_id
        );
    }

    /**
     * @return RequestFieldStatusListItem|null
     */
    public function getAccessStatus() : ?RequestFieldStatusListItem
    {
        return RequestFieldStatusList::getItem(
            $this->access_status_id
        );
    }

    /**
     * @return RequestFieldStatusListItem|null
     */
    public function getShowcaseStatus() : ?RequestFieldStatusListItem
    {
        return RequestFieldStatusList::getItem(
            $this->showcase_status_id
        );
    }

    /**
     * @return RequestFieldStatusListItem|null
     */
    public function getOrderAcceptStatus() : ?RequestFieldStatusListItem
    {
        return RequestFieldStatusList::getItem(
            $this->order_accept_status_id
        );
    }

    /**
     * @return RequestFieldStatusListItem|null
     */
    public function getStatusStatus() : ?RequestFieldStatusListItem
    {
        return RequestFieldStatusList::getItem(
            $this->status_status_id
        );
    }

    /**
     * @return RequestStatusListItem|null
     */
    public function getRequestStatus() : ?RequestStatusListItem
    {
        return RequestStatusList::getItem(
            $this->request_status_id
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
}
