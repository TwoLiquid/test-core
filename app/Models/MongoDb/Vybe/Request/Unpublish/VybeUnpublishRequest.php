<?php

namespace App\Models\MongoDb\Vybe\Request\Unpublish;

use App\Lists\Language\LanguageList;
use App\Lists\Language\LanguageListItem;
use App\Lists\Request\Field\Status\RequestFieldStatusList;
use App\Lists\Request\Field\Status\RequestFieldStatusListItem;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Lists\ToastMessage\Type\ToastMessageTypeList;
use App\Lists\ToastMessage\Type\ToastMessageTypeListItem;
use App\Lists\Vybe\Status\VybeStatusList;
use App\Lists\Vybe\Status\VybeStatusListItem;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\Vybe\Vybe;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MongoDB\Laravel\Eloquent\Model;

/**
 * App\Models\MongoDb\Vybe\Request\Unpublish\VybeUnpublishRequest
 *
 * @property int $_id
 * @property string $vybe_id
 * @property string $message
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
 * @property-read Admin $admin
 * @method static Builder|VybeUnpublishRequest find(string $id)
 * @method static Builder|VybeUnpublishRequest query()
 */
class VybeUnpublishRequest extends Model
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
    protected $collection = 'vybe_unpublish_requests';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vybe_id', 'message', 'status_id', 'previous_status_id', 'status_status_id', 'toast_message_type_id',
        'toast_message_text', 'request_status_id', 'shown', 'admin_id', 'language_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'shown' => 'boolean'
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

    //--------------------------------------------------------------------------
    // Lists accessors

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
