<?php

namespace App\Models\MongoDb\User\Request\IdVerification;

use App\Lists\Language\LanguageList;
use App\Lists\Language\LanguageListItem;
use App\Lists\Request\Field\Status\RequestFieldStatusList;
use App\Lists\Request\Field\Status\RequestFieldStatusListItem;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Lists\ToastMessage\Type\ToastMessageTypeList;
use App\Lists\ToastMessage\Type\ToastMessageTypeListItem;
use App\Lists\User\IdVerification\Status\UserIdVerificationStatusList;
use App\Lists\User\IdVerification\Status\UserIdVerificationStatusListItem;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\User\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MongoDB\Laravel\Eloquent\Model;

/**
 * App\Models\MongoDb\User\Request\IdVerification
 *
 * @property string $_id
 * @property int $user_id
 * @property int $verification_status_id
 * @property int $verification_status_status_id
 * @property int $previous_verification_status_id
 * @property bool $shown
 * @property int $request_status_id
 * @property int $toast_message_type_id
 * @property string $toast_message_text
 * @property int $admin_id
 * @property int $language_id
 * @property Carbon $created_at
 * @property-read User $user
 * @property-read Admin $admin
 * @method static Builder|UserIdVerificationRequest find(string $id)
 * @method static Builder|UserIdVerificationRequest query()
 */
class UserIdVerificationRequest extends Model
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
    protected $collection = 'user_id_verification_requests';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'verification_status_id', 'verification_status_status_id',
        'previous_verification_status_id', 'shown', 'toast_message_type_id',
        'toast_message_text', 'request_status_id', 'admin_id', 'language_id'
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
     * @return UserIdVerificationStatusListItem|null
     */
    public function getVerificationStatus() : ?UserIdVerificationStatusListItem
    {
        return UserIdVerificationStatusList::getItem(
            $this->verification_status_id
        );
    }

    /**
     * @return RequestFieldStatusListItem|null
     */
    public function getVerificationStatusStatus() : ?RequestFieldStatusListItem
    {
        return RequestFieldStatusList::getItem(
            $this->verification_status_status_id
        );
    }

    /**
     * @return UserIdVerificationStatusListItem|null
     */
    public function getPreviousVerificationStatus() : ?UserIdVerificationStatusListItem
    {
        return UserIdVerificationStatusList::getItem(
            $this->previous_verification_status_id
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
