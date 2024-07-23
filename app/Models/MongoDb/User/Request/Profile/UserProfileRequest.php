<?php

namespace App\Models\MongoDb\User\Request\Profile;

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
 * App\Models\MongoDb\User\Request\Profile
 *
 * @property string $_id
 * @property int $user_id
 * @property int $account_status_id
 * @property int $account_status_status_id
 * @property int $previous_account_status_id
 * @property string $username
 * @property int $username_status_id
 * @property string $previous_username
 * @property Carbon $birth_date
 * @property int $birth_date_status_id
 * @property string $previous_birth_date
 * @property string $description
 * @property int $description_status_id
 * @property string $previous_description
 * @property int $voice_sample_id
 * @property int $voice_sample_status_id
 * @property int $previous_voice_sample_id
 * @property int $avatar_id
 * @property int $avatar_status_id
 * @property int $previous_avatar_id
 * @property int $background_id
 * @property int $background_status_id
 * @property int $previous_background_id
 * @property array $images_ids
 * @property array $previous_images_ids
 * @property array $videos_ids
 * @property array $previous_videos_ids
 * @property int $album_status_id
 * @property int $toast_message_type_id
 * @property string $toast_message_text
 * @property int $request_status_id
 * @property int $admin_id
 * @property int $language_id
 * @property bool $shown
 * @property Carbon $created_at
 * @property-read User $user
 * @property-read Admin $admin
 * @method static Builder|UserProfileRequest find(string $id)
 * @method static Builder|UserProfileRequest query()
 */
class UserProfileRequest extends Model
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
    protected $collection = 'user_profile_requests';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'account_status_id', 'account_status_status_id', 'previous_account_status_id',
        'username', 'username_status_id', 'previous_username', 'birth_date', 'birth_date_status_id',
        'previous_birth_date', 'description', 'description_status_id', 'previous_description',
        'voice_sample_id', 'voice_sample_status_id', 'previous_voice_sample_id', 'avatar_id',
        'avatar_status_id', 'previous_avatar_id', 'background_id', 'background_status_id',
        'previous_background_id', 'images_ids', 'previous_images_ids', 'videos_ids', 'previous_videos_ids',
        'album_status_id', 'toast_message_type_id', 'toast_message_text', 'request_status_id',
        'admin_id', 'language_id', 'shown'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'birth_date' => 'datetime',
        'shown'      => 'boolean'
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
     * @return AccountStatusListItem|null
     */
    public function getPreviousAccountStatus() : ?AccountStatusListItem
    {
        return AccountStatusList::getItem(
            $this->previous_account_status_id
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
     * @return RequestFieldStatusListItem|null
     */
    public function getUsernameStatus() : ?RequestFieldStatusListItem
    {
        return RequestFieldStatusList::getItem(
            $this->username_status_id
        );
    }

    /**
     * @return RequestFieldStatusListItem|null
     */
    public function getBirthdateStatus() : ?RequestFieldStatusListItem
    {
        return RequestFieldStatusList::getItem(
            $this->birth_date_status_id
        );
    }

    /**
     * @return RequestFieldStatusListItem|null
     */
    public function getDescriptionStatus() : ?RequestFieldStatusListItem
    {
        return RequestFieldStatusList::getItem(
            $this->description_status_id
        );
    }

    /**
     * @return RequestFieldStatusListItem|null
     */
    public function getVoiceSampleStatus() : ?RequestFieldStatusListItem
    {
        return RequestFieldStatusList::getItem(
            $this->voice_sample_status_id
        );
    }

    /**
     * @return RequestFieldStatusListItem|null
     */
    public function getAvatarStatus() : ?RequestFieldStatusListItem
    {
        return RequestFieldStatusList::getItem(
            $this->avatar_status_id
        );
    }

    /**
     * @return RequestFieldStatusListItem|null
     */
    public function getBackgroundStatus() : ?RequestFieldStatusListItem
    {
        return RequestFieldStatusList::getItem(
            $this->background_status_id
        );
    }

    /**
     * @return RequestFieldStatusListItem|null
     */
    public function getAlbumStatus() : ?RequestFieldStatusListItem
    {
        return RequestFieldStatusList::getItem(
            $this->album_status_id
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
