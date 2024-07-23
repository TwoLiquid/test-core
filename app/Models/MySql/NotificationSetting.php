<?php

namespace App\Models\MySql;

use App\Models\MySql\User\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Eloquent;

/**
 * App\Models\MySql\NotificationSetting
 *
 * @property int $id
 * @property int|null $user_id
 * @property bool $notification_enable
 * @property bool $email_followers_follows_you
 * @property bool $email_followers_new_vybe
 * @property bool $email_followers_new_event
 * @property bool $messages_unread
 * @property bool $email_orders_new
 * @property bool $email_order_starts
 * @property bool $email_order_pending
 * @property bool $reschedule_info
 * @property bool $review_new
 * @property bool $review_waiting
 * @property bool $withdrawals_info
 * @property bool $email_invitation_info
 * @property bool $tickets_new_order
 * @property bool $miscellaneous_regarding
 * @property bool $platform_followers_follows
 * @property bool $platform_followers_new_vybe
 * @property bool $platform_followers_new_event
 * @property bool $platform_order_starts
 * @property bool $platform_invitation_info
 * @property bool $news_receive
 * @property-read User|null $user
 * @method static Builder|NotificationSetting newModelQuery()
 * @method static Builder|NotificationSetting newQuery()
 * @method static Builder|NotificationSetting query()
 * @method static Builder|NotificationSetting whereEmailFollowersFollowsYou($value)
 * @method static Builder|NotificationSetting whereEmailFollowersNewEvent($value)
 * @method static Builder|NotificationSetting whereEmailFollowersNewVybe($value)
 * @method static Builder|NotificationSetting whereEmailInvitationInfo($value)
 * @method static Builder|NotificationSetting whereEmailOrderPending($value)
 * @method static Builder|NotificationSetting whereEmailOrderStarts($value)
 * @method static Builder|NotificationSetting whereEmailOrdersNew($value)
 * @method static Builder|NotificationSetting whereId($value)
 * @method static Builder|NotificationSetting whereMessagesUnread($value)
 * @method static Builder|NotificationSetting whereMiscellaneousRegarding($value)
 * @method static Builder|NotificationSetting whereNewsReceive($value)
 * @method static Builder|NotificationSetting whereNotificationEnable($value)
 * @method static Builder|NotificationSetting wherePlatformFollowersFollows($value)
 * @method static Builder|NotificationSetting wherePlatformFollowersNewEvent($value)
 * @method static Builder|NotificationSetting wherePlatformFollowersNewVybe($value)
 * @method static Builder|NotificationSetting wherePlatformInvitationInfo($value)
 * @method static Builder|NotificationSetting wherePlatformOrderStarts($value)
 * @method static Builder|NotificationSetting whereRescheduleInfo($value)
 * @method static Builder|NotificationSetting whereReviewNew($value)
 * @method static Builder|NotificationSetting whereReviewWaiting($value)
 * @method static Builder|NotificationSetting whereTicketsNewOrder($value)
 * @method static Builder|NotificationSetting whereUserId($value)
 * @method static Builder|NotificationSetting whereWithdrawalsInfo($value)
 * @mixin Eloquent
 */
class NotificationSetting extends Model
{
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
    protected $table = 'notification_settings';

    /**
     * Provide default timestamps usage
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'notification_enable', 'email_followers_follows_you', 'email_followers_new_vybe',
        'email_followers_new_event', 'messages_unread', 'email_orders_new', 'email_order_starts',
        'email_order_pending', 'reschedule_info', 'review_new', 'review_waiting', 'withdrawals_info',
        'email_invitation_info', 'tickets_new_order', 'miscellaneous_regarding', 'platform_followers_follows',
        'platform_followers_new_vybe', 'platform_followers_new_event', 'platform_order_starts',
        'platform_invitation_info', 'news_receive'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'notification_enable'          => 'boolean',
        'email_followers_follows_you'  => 'boolean',
        'email_followers_new_vybe'     => 'boolean',
        'email_followers_new_event'    => 'boolean',
        'messages_unread'              => 'boolean',
        'email_orders_new'             => 'boolean',
        'email_order_starts'           => 'boolean',
        'email_order_pending'          => 'boolean',
        'reschedule_info'              => 'boolean',
        'review_new'                   => 'boolean',
        'review_waiting'               => 'boolean',
        'withdrawals_info'             => 'boolean',
        'email_invitation_info'        => 'boolean',
        'tickets_new_order'            => 'boolean',
        'miscellaneous_regarding'      => 'boolean',
        'platform_followers_follows'   => 'boolean',
        'platform_followers_new_vybe'  => 'boolean',
        'platform_followers_new_event' => 'boolean',
        'platform_order_starts'        => 'boolean',
        'platform_invitation_info'     => 'boolean',
        'news_receive'                 => 'boolean'
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
}
