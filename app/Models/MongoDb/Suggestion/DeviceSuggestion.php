<?php

namespace App\Models\MongoDb\Suggestion;

use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Models\MongoDb\Vybe\Request\Change\VybeChangeRequest;
use App\Models\MongoDb\Vybe\Request\Publish\VybePublishRequest;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\Device;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MongoDB\Laravel\Relations\BelongsTo as MongoBelongsTo;
use MongoDB\Laravel\Eloquent\Model;

/**
 * App\Models\MongoDb\Suggestion\DeviceSuggestion
 *
 * @property string $_id
 * @property string $vybe_publish_request_id
 * @property string $vybe_change_request_id
 * @property int $device_id
 * @property string $suggestion
 * @property bool $visible
 * @property int $admin_id
 * @property int $status_id
 * @property Carbon $created_at
 * @property-read VybePublishRequest $vybePublishRequest
 * @property-read VybeChangeRequest $vybeChangeRequest
 * @property-read Device $device
 * @property-read Admin $admin
 * @method static Builder|DeviceSuggestion find(string $id)
 * @method static Builder|DeviceSuggestion query()
 */
class DeviceSuggestion extends Model
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
    protected $collection = 'device_suggestions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vybe_publish_request_id', 'vybe_change_request_id', 'device_id',
        'suggestion', 'visible', 'admin_id', 'status_id'
    ];

    //--------------------------------------------------------------------------
    // Belongs to relations

    /**
     * @return MongoBelongsTo
     */
    public function vybePublishRequest() : MongoBelongsTo
    {
        return $this->belongsTo(VybePublishRequest::class);
    }

    /**
     * @return MongoBelongsTo
     */
    public function vybeChangeRequest() : MongoBelongsTo
    {
        return $this->belongsTo(VybeChangeRequest::class);
    }

    /**
     * @return BelongsTo
     */
    public function device() : BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    /**
     * @return BelongsTo
     */
    public function admin() : BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    //--------------------------------------------------------------------------
    // Lists accessors

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
