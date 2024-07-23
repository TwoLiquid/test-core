<?php

namespace App\Models\MongoDb;

use App\Lists\Language\LanguageList;
use App\Lists\Language\LanguageListItem;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\Receipt\WithdrawalReceipt;
use App\Models\MySql\User\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use MongoDB\Laravel\Eloquent\Model;

/**
 * App\Models\MongoDb\WithdrawalRequest
 *
 * @property string $_id
 * @property int $int_id
 * @property int $user_id
 * @property int $method_id
 * @property int $receipt_id
 * @property float $amount
 * @property bool $shown
 * @property int $status_id
 * @property int $toast_message_type_id
 * @property string $toast_message_text
 * @property int $admin_id
 * @property int $language_id
 * @property Carbon $created_at
 * @property-read User $user
 * @property-read PaymentMethod $method
 * @property-read WithdrawalReceipt $receipt
 * @property-read Admin $admin
 * @method static Builder|WithdrawalRequest find(string $id)
 * @method static Builder|WithdrawalRequest query()
 */
class WithdrawalRequest extends Model
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
    protected $collection = 'withdrawal_requests';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'int_id', 'user_id', 'method_id', 'receipt_id', 'amount', 'shown', 'status_id',
        'toast_message_type_id', 'toast_message_text', 'admin_id', 'language_id'
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
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

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
    public function receipt() : BelongsTo
    {
        return $this->belongsTo(WithdrawalReceipt::class);
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
