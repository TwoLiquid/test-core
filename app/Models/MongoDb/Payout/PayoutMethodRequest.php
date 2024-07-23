<?php

namespace App\Models\MongoDb\Payout;

use App\Lists\Language\LanguageList;
use App\Lists\Language\LanguageListItem;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Lists\ToastMessage\Type\ToastMessageTypeList;
use App\Lists\ToastMessage\Type\ToastMessageTypeListItem;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\User\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\HasMany;

/**
 * App\Models\MongoDb\Payout\PayoutMethodRequest
 *
 * @property string $_id
 * @property int $method_id
 * @property int $user_id
 * @property bool $shown
 * @property int $request_status_id
 * @property int $toast_message_type_id
 * @property string $toast_message_text
 * @property int $admin_id
 * @property int $language_id
 * @property Carbon $created_at
 * @property-read PaymentMethod $method
 * @property-read User $user
 * @property-read Admin $admin
 * @property-read PayoutMethodRequestField[] $fields
 * @method static Builder|PayoutMethodRequest find(string $id)
 * @method static Builder|PayoutMethodRequest query()
 */
class PayoutMethodRequest extends Model
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
    protected $collection = 'payout_method_requests';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'method_id', 'user_id', 'shown', 'request_status_id', 'toast_message_type_id',
        'toast_message_text', 'admin_id', 'language_id'
    ];

    //--------------------------------------------------------------------------
    // Belongs to relations

    /**
     * @return BelongsTo
     */
    public function method() : BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    /**
     * @return BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function admin() : BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    //--------------------------------------------------------------------------
    // Has many relations

    /**
     * @return HasMany
     */
    public function fields() : HasMany
    {
        return $this->hasMany(
            PayoutMethodRequestField::class,
            'request_id',
            'id'
        );
    }

    //--------------------------------------------------------------------------
    // Lists accessors

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
