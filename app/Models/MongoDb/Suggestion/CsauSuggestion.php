<?php

namespace App\Models\MongoDb\Suggestion;

use App\Lists\Request\Field\Status\RequestFieldStatusList;
use App\Lists\Request\Field\Status\RequestFieldStatusListItem;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Models\MongoDb\Vybe\Request\Change\VybeChangeRequest;
use App\Models\MongoDb\Vybe\Request\Publish\VybePublishRequest;
use App\Models\MySql\Activity\Activity;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\Category;
use App\Models\MySql\Unit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MongoDB\Laravel\Eloquent\Model;

/**
 * App\Models\MongoDb\Suggestion\CsauSuggestion
 *
 * @property string $_id
 * @property string $vybe_publish_request_id
 * @property string $vybe_change_request_id
 * @property int $category_id
 * @property string $category_suggestion
 * @property int $category_status_id
 * @property int $subcategory_id
 * @property string $subcategory_suggestion
 * @property int $subcategory_status_id
 * @property int $activity_id
 * @property string $activity_suggestion
 * @property int $activity_status_id
 * @property int $unit_id
 * @property string $unit_suggestion
 * @property int $unit_duration
 * @property int $unit_status_id
 * @property bool $visible
 * @property int $admin_id
 * @property int $status_id
 * @property Carbon $created_at
 * @property-read VybePublishRequest $vybePublishRequest
 * @property-read VybeChangeRequest $vybeChangeRequest
 * @property-read Activity $activity
 * @property-read Category $category
 * @property-read Category $subcategory
 * @property-read Unit $unit
 * @property-read Admin $admin
 * @method static Builder|CsauSuggestion find(string $id)
 * @method static Builder|CsauSuggestion query()
 */
class CsauSuggestion extends Model
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
    protected $collection = 'csau_suggestions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vybe_publish_request_id', 'vybe_change_request_id', 'category_id', 'category_suggestion',
        'category_status_id', 'subcategory_id', 'subcategory_suggestion', 'subcategory_status_id', 'activity_id',
        'activity_suggestion', 'activity_status_id', 'unit_id', 'unit_suggestion', 'unit_duration',
        'unit_status_id', 'visible', 'admin_id', 'status_id'
    ];

    //--------------------------------------------------------------------------
    // Belongs to relations

    /**
     * @return BelongsTo
     */
    public function vybePublishRequest() : BelongsTo
    {
        return $this->belongsTo(VybePublishRequest::class);
    }

    /**
     * @return BelongsTo
     */
    public function vybeChangeRequest() : BelongsTo
    {
        return $this->belongsTo(VybeChangeRequest::class);
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
    public function subcategory() : BelongsTo
    {
        return $this->belongsTo(Category::class);
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
    public function unit() : BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * @return BelongsTo
     */
    public function admin() : BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    //--------------------------------------------------------------------------
    // Suggestion statuses

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
    public function getUnitStatus() : ?RequestFieldStatusListItem
    {
        return RequestFieldStatusList::getItem(
            $this->unit_status_id
        );
    }

    /**
     * @return RequestStatusListItem|null
     */
    public function getStatus() : ?RequestStatusListItem
    {
        return RequestStatusList::getItem(
            $this->status_id
        );
    }
}
