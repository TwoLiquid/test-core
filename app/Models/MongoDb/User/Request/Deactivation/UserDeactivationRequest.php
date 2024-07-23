<?php

namespace App\Models\MongoDb\User\Request\Deactivation;

use App\Lists\Account\Status\AccountStatusList;
use App\Lists\Account\Status\AccountStatusListItem;
use App\Lists\Language\LanguageList;
use App\Lists\Language\LanguageListItem;
use App\Lists\Request\Field\Status\RequestFieldStatusList;
use App\Lists\Request\Field\Status\RequestFieldStatusListItem;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Lists\ToastMessage\Type\ToastMessageTypeList;
use App\Lists\ToastMessage\Type\ToastMessageTypeListItem;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\User\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MongoDB\Laravel\Eloquent\Model;

/**
 * App\Models\MongoDb\User\Request\Deactivation
 *
 * @property string $_id
 * @property int $user_id
 * @property string $reason
 * @property int $account_status_id
 * @property int $account_status_status_id
 * @property int $previous_account_status_id
 * @property int $request_status_id
 * @property bool $shown
 * @property int $toast_message_type_id
 * @property string $toast_message_text
 * @property int $admin_id
 * @property int $language_id
 * @property Carbon $created_at
 * @property-read User $user
 * @property-read Admin $admin
 * @method static Builder|UserDeactivationRequest find(string $id)
 * @method static Builder|UserDeactivationRequest query()
 */
class UserDeactivationRequest extends Model
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
    protected $collection = 'user_deactivation_requests';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'reason', 'account_status_id', 'account_status_status_id',
        'previous_account_status_id', 'toast_message_type_id', 'toast_message_text', 'shown',
        'request_status_id', 'admin_id', 'language_id'
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
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    //--------------------------------------------------------------------------
    // Lists accessors

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
     * @return RequestFieldStatusListItem|null
     */
    public function getAccountStatusStatus() : ?RequestFieldStatusListItem
    {
        return RequestFieldStatusList::getItem(
            $this->account_status_status_id
        );
    }

    /**
     * @return AccountStatusListItem|null
     */
    public function getPreviousAccountStatus() : ?AccountStatusListItem
    {
        return AccountStatusList::getItem(
            $this->previous_account_status_id
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
